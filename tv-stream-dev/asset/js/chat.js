var _slicedToArray = function () { function sliceIterator(arr, i) { var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"]) _i["return"](); } finally { if (_d) throw _e; } } return _arr; } return function (arr, i) { if (Array.isArray(arr)) { return arr; } else if (Symbol.iterator in Object(arr)) { return sliceIterator(arr, i); } else { throw new TypeError("Invalid attempt to destructure non-iterable instance"); } }; }();

/* Helpful information:

Clips
	Endpoint: https://api.twitch.tv/kraken/clips/ReliableSplendidInternPogChamp?on_site=1&api_version=5
	Exmpample Clip: https://clips.twitch.tv/ReliableSplendidInternPogChamp
	Missing thumbnail: https://clips-media-assets.twitch.tv/404-preview-86x45.jpg
	Broken thumbnail: https://clips-media-assets.twitch.tv/vod-153090723-offset-1928.5-60-preview-1920x1080.jpg

	*/
	//var dj = JSON.parse('{"test"}');
	var chatEle = document.getElementById('live-chat-msg');
	var formu   = document.getElementById('live-chat-form');
	var formmsg = document.getElementById('live-chat-post');
	//dj -> 262 275
	var twitchBadgeCache = {
		data: { global: {} }
	};
	var bttvEmoteCache = {
		lastUpdated: 0,
		data: { global: [] },
		urlTemplate: '//cdn.betterttv.net/emote/{{id}}/{{image}}'
	};

	var krakenBase = 'https://api.twitch.tv/kraken/';
	var krakenClientID = 'egc8580xn3rwjxcf3ogfi4hbyyww28';
	
var chatFilters = [/*'\xC0-\xD6\xD8-\xF6\xF8-\xFF', // Partial Latin-1 Supplement
'\u0100-\u017F', // Latin Extended-A
'\u0180-\u024F', // Latin Extended-B
'\u0250-\u02AF', // IPA Extensions
'\u02B0-\u02FF', // Spacing Modifier Letters
'\u0300-\u036F', // Combining Diacritical Marks
'\u0370-\u03FF', // Greek and Coptic
'\u0400-\u04FF', // Cyrillic
'\u0500-\u052F', // Cyrillic Supplement
'\u0530-\u1FFF', // Bunch of non-English
'\u2100-\u214F', // Letter Like
'\u2500-\u257F', // Box Drawing
'\u2580-\u259F', // Block Elements
'\u25A0-\u25FF', // Geometric Shapes
'\u2600-\u26FF', // Miscellaneous Symbols
// '\u2700-\u27BF', // Dingbats
'\u2800-\u28FF', // Braille
'\u2C60-\u2C7F'*/];//d ont say this
var chatFilter = new RegExp('[' + chatFilters.join('') + ']');

var client = void 0;

kraken({ // Temporary
	endpoint: 'streams',
	qs: {
		limit: 10,
		language: 'en'
	}
}).then(function (_ref) {
	var streams = _ref.streams;
	var options = {
		options: {debug: true},
		connection: 
		{
			reconnect: true,
			secure: true
		},
		identity: {},
		channels: [ 'mrdjcraft' ]
		/*channels: streams.map(function (n) {
			return n.channel.name;
		})*/
	};
	if(token!="" && uname!="")
	{
		formmsg.disabled=false;
		options.identity={username: uname,password: ("oauth:"+token)};
	}
	client = new tmi.client(options);
	addListeners();
	client.connect();
});

function addListeners() {
	client.on('connecting', function () {
		showAdminMessage({
			message: 'Connecting...',
			attribs: { subtype: 'connecting' }
		});
		removeAdminChatLine({ subtype: 'disconnected' });
	});

	client.on('connected', function () {
		getBTTVEmotes();
		getBadges().then(function (badges) {
			return twitchBadgeCache.data.global = badges;
		});
		showAdminMessage({
			message: 'Connected...',
			attribs: { subtype: 'connected' },
			timeout: 5000
		});
		removeAdminChatLine({ subtype: 'connecting' });
		removeAdminChatLine({ subtype: 'disconnected' });
	});

	client.on('disconnected', function () {
		twitchBadgeCache.data = { global: {} };
		bttvEmoteCache.data = { global: [] };
		showAdminMessage({
			message: 'Disconnected...',
			attribs: { subtype: 'disconnected' }
		});
		removeAdminChatLine({ subtype: 'connecting' });
		removeAdminChatLine({ subtype: 'connected' });
	});

	function handleMessage(channel, userstate, message, fromSelf) {
		if (chatFilter.test(message)) {
			console.log(message);
			return;
		}

		var chan = getChan(channel);
		var name = userstate['display-name'] || userstate.username;
		if (/[^\w]/g.test(name)) {
			name += ' (' + userstate.username + ')';
		}
		userstate.name = name;
		showMessage({ chan: chan, type: 'chat', message: message, data: userstate });
	}

	client.on('message', handleMessage);
	client.on('cheer', handleMessage);

	client.on('join', function (channel, username, self) {
		if (!self) {
			return;
		}
		var chan = getChan(channel);
		getBTTVEmotes(chan);
		twitchNameToUser(chan).then(function (user) {
			return getBadges(user._id);
		}).then(function (badges) {
			return twitchBadgeCache.data[chan] = badges;
		});
		showAdminMessage({
			message: 'Joined ' + chan,
			timeout: 1000
		});
	});

	client.on('part', function (channel, username, self) {
		if (!self) {
			return;
		}
		var chan = getChan(channel);
		delete bttvEmoteCache.data[chan];
		showAdminMessage({
			message: 'Parted ' + chan,
			timeout: 1000
		});
	});

	client.on('clearchat', function (channel) {
		removeChatLine({ channel: channel });
	});

	client.on('timeout', function (channel, username) {
		removeChatLine({ channel: channel, username: username });
	});
}

function removeChatLine() {
	var params = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};

	if ('channel' in params) {
		params.channel = getChan(params.channel);
	}
	var search = Object.keys(params).map(function (key) {
		return '[' + key + '="' + params[key] + '"]';
	}).join('');
	chatEle.querySelectorAll(search).forEach(function (n) {
		return chatEle.removeChild(n);
	});
}

function removeAdminChatLine() {
	var params = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};

	params.type = 'admin';
	removeChatLine(params);
}

function showAdminMessage(opts) {
	opts.type = 'admin';
	if ('attribs' in opts === false) {
		opts.attribs = {};
	}
	opts.attribs.type = 'admin';
	return showMessage(opts);
}

function getChan() {
	var channel = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '';

	return channel.replace(/^#/, '');
}

function showMessage() {
	var _ref2 = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {},
	chan = _ref2.chan,
	type = _ref2.type,
	_ref2$message = _ref2.message,
	message = _ref2$message === undefined ? '' : _ref2$message,
	_ref2$data = _ref2.data,
	data = _ref2$data === undefined ? {} : _ref2$data,
	_ref2$timeout = _ref2.timeout,
	timeout = _ref2$timeout === undefined ? 0 : _ref2$timeout,
	_ref2$attribs = _ref2.attribs,
	attribs = _ref2$attribs === undefined ? {} : _ref2$attribs;

	var chatLine_ = document.createElement('div');
	var chatLine = document.createElement('div');
	chatLine_.classList.add('chat-line');
	chatLine.classList.add('chat-line-inner');
	chatLine_.appendChild(chatLine);

	if (chan) {
		chatLine_.setAttribute('channel', chan);
	}

	Object.keys(attribs).forEach(function (key) {
		chatLine_.setAttribute(key, attribs[key]);
	});

	if (type === 'chat') {
		'id' in data && chatLine_.setAttribute('message-id', data.id);
		'user-id' in data && chatLine_.setAttribute('user-id', data['user-id']);
		'room-id' in data && chatLine_.setAttribute('channel-id', data['room-id']);
		'username' in data && chatLine_.setAttribute('username', data.username);

		var spaceEle = document.createElement('span');
		spaceEle.innerText = ' ';
		var badgeEle = document.createElement('span');
		if ('badges' in data && data.badges !== null) {
			badgeEle.classList.add('badges');
			var badgeGroup = Object.assign({}, twitchBadgeCache.data.global, twitchBadgeCache.data[chan] || {});
			var badges = Object.keys(data.badges).forEach(function (type) {
				var version = data.badges[type];
				var group = badgeGroup[type];
				if (group && version in group.versions) {
					var url = group.versions[version].image_url_1x;
					var ele = document.createElement('img');
					ele.setAttribute('src', url);
					ele.setAttribute('badgeType', type);
					ele.setAttribute('alt', type);
					ele.setAttribute('height',"20em");
					ele.classList.add('badge');
					badgeEle.appendChild(ele);
				}
			}, []);
		}
		///////////////////////////////////////////////////////
		var dj = document.createElement('span');
		dj.classList.add('badges');
		$.getJSON( "/djbadge.php?u="+data.name, function(djson){
			if(djson.length != 0)
			{
				var djb = document.createElement('img');
				djb.setAttribute('src', '/asset/img/djbadge/'+djson.url);
				djb.setAttribute('alt', djson.name);
				djb.setAttribute('height',"20em");
				djb.classList.add('badge');
				dj.appendChild(djb);
			}
		})
		chatLine.appendChild(dj);
		////////////////////////////////////////////////////////
		var nameEle = document.createElement('span');
		nameEle.classList.add('user-name');
		nameEle.innerText = data.name;

		var colonEle = document.createElement('span');
		colonEle.classList.add('message-colon');
		colonEle.innerText = ': ';

		var messageEle = document.createElement('span');
		messageEle.classList.add('message');

		var finalMessage = handleEmotes(chan, data.emotes || {}, message);
		addEmoteDOM(messageEle, finalMessage);

		chatLine.appendChild(badgeEle);
		chatLine.appendChild(spaceEle);
		chatLine.appendChild(nameEle);
		chatLine.appendChild(colonEle);
		chatLine.appendChild(messageEle);
	} else if (type === 'admin') {
		chatLine_.classList.add('admin');

		var _messageEle = document.createElement('span');
		_messageEle.classList.add('message');
		_messageEle.innerText = message;

		chatLine.appendChild(_messageEle);
	}

	chatEle.appendChild(chatLine_);

	if (chatEle.childElementCount > 30) {
		chatEle.removeChild(chatEle.children[0]);
	}

	if (timeout) {
		setTimeout(function () {
			if (chatLine_.parentElement) {
				chatEle.removeChild(chatLine_);
			}
		}, timeout);
	}
}

function handleEmotes(channel, emotes, message) {
	// let messageParts = message.split(' ');
	var bttvEmotes = bttvEmoteCache.data.global.slice(0);
	if (channel in bttvEmoteCache.data) {
		bttvEmotes = bttvEmotes.concat(bttvEmoteCache.data[channel]);
	}
	var twitchEmoteKeys = Object.keys(emotes);
	var allEmotes = twitchEmoteKeys.reduce(function (p, id) {
		var emoteData = emotes[id].map(function (n) {
			var _n$split = n.split('-'),
			_n$split2 = _slicedToArray(_n$split, 2),
			a = _n$split2[0],
			b = _n$split2[1];

			var start = +a;
			var end = +b + 1;
			return {
				start: start,
				end: end,
				id: id,
				code: message.slice(start, end),
				type: ['twitch', 'emote']
			};
		});
		return p.concat(emoteData);
	}, []);
	bttvEmotes.forEach(function (_ref3) {
		var code = _ref3.code,
		id = _ref3.id,
		type = _ref3.type,
		imageType = _ref3.imageType;

		var hasEmote = message.indexOf(code);
		if (hasEmote === -1) {
			return;
		}
		for (var start = message.indexOf(code); start > -1; start = message.indexOf(code, start + 1)) {
			var end = start + code.length;
			allEmotes.push({ start: start, end: end, id: id, code: code, type: type });
		}
	});
	var seen = [];
	allEmotes = allEmotes.sort(function (a, b) {
		return a.start - b.start;
	}).filter(function (_ref4) {
		var start = _ref4.start,
		end = _ref4.end;

		if (seen.length && !seen.every(function (n) {
			return start > n.end;
		})) {
			return false;
		}
		seen.push({ start: start, end: end });
		return true;
	});
	if (allEmotes.length) {
		var finalMessage = [message.slice(0, allEmotes[0].start)];
		allEmotes.forEach(function (n, i) {
			var p = Object.assign({}, n, { i: i });
			var end = p.end;

			finalMessage.push(p);
			if (i === allEmotes.length - 1) {
				finalMessage.push(message.slice(end));
			} else {
				finalMessage.push(message.slice(end, allEmotes[i + 1].start));
			}
			finalMessage = finalMessage.filter(function (n) {
				return n;
			});
		});
		return finalMessage;
	}
	return [message];
}

function addEmoteDOM(ele, data) {
	data.forEach(function (n) {
		var out = null;
		if (typeof n === 'string') {
			out = document.createTextNode(n);
		} else {
			var _n$type = _slicedToArray(n.type, 2),
			type = _n$type[0],
			subtype = _n$type[1],
			code = n.code;

			if (type === 'twitch') {
				if (subtype === 'emote') {
					out = document.createElement('img');
					out.setAttribute('src', 'https://static-cdn.jtvnw.net/emoticons/v1/' + n.id + '/1.0');
					out.setAttribute('alt', code);
				}
			} else if (type === 'bttv') {
				out = document.createElement('img');
				var url = bttvEmoteCache.urlTemplate;
				url = url.replace('{{id}}', n.id).replace('{{image}}', '1x');
				out.setAttribute('src', 'https:' + url);
			}
		}

		if (out) {
			ele.appendChild(out);
		}
	});
	twemoji.parse(ele);
}

function formQuerystring() {
	var qs = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};

	return Object.keys(qs).map(function (key) {
		return key + '=' + qs[key];
	}).join('&');
}

function request(_ref5) {
	var _ref5$base = _ref5.base,
	base = _ref5$base === undefined ? '' : _ref5$base,
	_ref5$endpoint = _ref5.endpoint,
	endpoint = _ref5$endpoint === undefined ? '' : _ref5$endpoint,
	qs = _ref5.qs,
	_ref5$headers = _ref5.headers,
	headers = _ref5$headers === undefined ? {} : _ref5$headers,
	_ref5$method = _ref5.method,
	method = _ref5$method === undefined ? 'get' : _ref5$method;

	var opts = {
		method: method,
		headers: new Headers(headers)
	};
	return fetch(base + endpoint + '?' + formQuerystring(qs), opts).then(function (res) {
		return res.json();
	});
}

function kraken(opts) {
	var defaults = {
		base: krakenBase,
		headers: {
			'Client-ID': krakenClientID,
			Accept: 'application/vnd.twitchtv.v5+json'
		}
	};
	return request(Object.assign(defaults, opts));
}

function twitchNameToUser(username) {
	return kraken({
		endpoint: 'users',
		qs: { login: username }
	}).then(function (_ref6) {
		var users = _ref6.users;
		return users[0] || null;
	});
}

function getBadges(channel) {
	return kraken({
		base: 'https://badges.twitch.tv/v1/badges/',
		endpoint: (channel ? 'channels/' + channel : 'global') + '/display',
		qs: { language: 'en' }
	}).then(function (data) {
		return data.badge_sets;
	});
}

function getClip(clipSlug) {
	return kraken({
		endpoint: 'clips/' + clipSlug
	});
}

function getBTTVEmotes(channel) {
	var endpoint = 'emotes';
	var global = true;
	if (channel) {
		endpoint = 'channels/' + channel;
		global = false;
	}
	return request({
		base: 'https://api.betterttv.net/2/',
		endpoint: endpoint
	}).then(function (_ref7) {
		var emotes = _ref7.emotes,
		status = _ref7.status,
		urlTemplate = _ref7.urlTemplate;

		if (status === 404) return;
		bttvEmoteCache.urlTemplate = urlTemplate;
		emotes.forEach(function (n) {
			n.global = global;
			n.type = ['bttv', 'emote'];
			if (!global) {
				if (channel in bttvEmoteCache.data === false) {
					bttvEmoteCache.data[channel] = [];
				}
				bttvEmoteCache.data[channel].push(n);
			} else {
				bttvEmoteCache.data.global.push(n);
			}
		});
	});
}
formu.addEventListener('submit', function(e) {
	e.preventDefault();
	if(formmsg.value == ""){console.log("err form empty");}
	else{client.say("#mrdjcraft",formmsg.value);formmsg.value="";}
});
formu.addEventListener('keypress', function(e) {
	if(e.key==="Enter")
	{
	if(formmsg.value == ""){console.log("err form empty");}
	else{client.say("#mrdjcraft",formmsg.value);formmsg.value="";}	
	}
});
