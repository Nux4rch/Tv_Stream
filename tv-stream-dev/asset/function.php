<?php
	function code($texte)
	{
		///Mise en forme du texte
		//br
		$texte = nl2br ( $texte );
		//gras
		$texte = preg_replace('`\[g\](.+)\[/g\]`isU', '<strong>$1</strong>', $texte); 
		//italique
		$texte = preg_replace('`\[i\](.+)\[/i\]`isU', '<em>$1</em>', $texte);
		//souligné
		$texte = preg_replace('`\[s\](.+)\[/s\]`isU', '<u>$1</u>', $texte);
		//lien
		$texte = preg_replace('#http://[a-z0-9._/-]+#i', '<a href="$0">$0</a>', $texte);
		$texte = preg_replace('`\[url src=(.+),name=(.+)\]`isU', '<a href="$1">$2</a>', $texte);
		//img
		$texte = preg_replace('`\[img src=(.+)\]`isU', '<img src="$1" height="50px">', $texte);
		$texte = preg_replace('`\[img src=(.+),name=(.+)\]`isU', '<img src="$1" alt="$2"height="50px">', $texte);
		
		return $texte;
	}	
