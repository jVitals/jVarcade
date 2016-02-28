jVArcade 2.13

Arcade games component for Joomla

<p><b>Install Notes:</b></p>
<p>For game archives that have a gamedata folder a .htaccess rule is require as follows:</p>
<p>
RewriteCond %{QUERY_STRING} ^(arcade/gamedata/$1)?$<br>
RewriteRule /arcade/gamedata/(.*) arcade/gamedata/$1 [NC]
</p>
<p>If using joomlas url rewrite rules in htaccess this rule must be placed before any other rules are processed. This applies to apache only not IIS and webconfig file.The below code is added directly below RewriteEngine On</p>
<p>
#RULE GAMEDATA FOR URL REWRITE<br>
RewriteRule ^.*/arcade/gamedata/(.*)$ arcade/gamedata/$1 [NC]
</p>
<p>This rule solved an issue when site was hosted by 1and1 hosting.</p>
<p>
AddType x-mapp-php5 .php<br>
RewriteEngine On<br>
RewriteBase /<br>
RewriteCond %{QUERY_STRING} ^(arcade/gamedata/$1)?$<br>
RewriteRule /arcade/gamedata/(.*) arcade/gamedata/$1 [NC]<br>
</p>

