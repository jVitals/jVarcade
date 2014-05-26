jVArcade 2.12

Arcade games component for Joomla

<p><b>Install Note:</b></p>
<p>For game archives that have a gamedata folder a .htaccess rule is require as follows:

RewriteCond %{QUERY_STRING} ^(arcade/gamedata/$1)?$ 
RewriteRule /arcade/gamedata/(.*) arcade/gamedata/$1 [NC]
</p>

