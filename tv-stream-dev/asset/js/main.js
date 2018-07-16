//pizza ?
var OnMangeQuoiCeSoir=true;
function c()
{
	if(!OnMangeQuoiCeSoir)
	{
		$("#mcdo").removeClass("open");
		console.log("close");
		OnMangeQuoiCeSoir=true;
	}
	else
	{
		$("#mcdo").addClass("open");
		console.log("open");
		OnMangeQuoiCeSoir=false;
	}
}
//badges selector
$('#badges-select').children().click(function(e)
{
	e.preventDefault();
	$.getJSON( e.currentTarget.href +"&r=1" , function(djson)
	{
		var bact=$('#badges-selected').children();
		bact.attr('src', e.target.src );
		bact.attr('alt', e.target.alt );
	})
	.fail(function(a,b,e) {console.log( "error :" , e );})
	.always(function() {console.log( "complete" );});
});
