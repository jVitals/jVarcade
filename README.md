jVArcade 2.1

Arcade games component for Joomla 3.2

<p><b>Install Note:</b></p>
<p>Install and enable jvfixscript and enable fix backend in settings.</p>

<p><b>Known Bugs:</b></p>

<p>BUG: Uploading archive in tar format fails when php version is 5.5 + <br>
CAUSE: JArchive tar adapter issue. <br>
FIX: To be addressed by joomla core update <br>
REFERENCE: https://github.com/joomla/joomla-framework-archive/commit/89d15271ac6e339657ca349d56226d550e94156c</p>

<p>BUG: Score saving for games that post to index.php creates an endless loop <br>
CAUSE: Unknown. Pending further investigation. Seems to have something to do with url params. EG: act=Arcade&do=newscore</p>

<p>BUG: Filter for list in administration manage games view does not work.<br>
CAUSE: Unknown. Pending further investigation.</p>

<p><b>Updates:</b></p>

<p>Replaced jstella atari applet with javartari330 in the atari plugin.<br>
Added support for Commodore 64 .prg with use of jac64 applet in the c64 plugin.<br>
Added support for game downloading with administration options for group permission and enable/disable.<br>
Used hard coded width and height in /views/game/default.php for games that use atari,c64,nes applets to prevent issues with config files not setting attributes when uploading.<br>
Rounded corners no longer uses javascript/jquey. Added rounded.corners.css to prevent script conflicts.<br>
Removed migration from puarcade. Note: Migration coding left in place for possible adaption to migration from Joomla 2.5</p>

<p><b>Fixes:</b></p>

<p>Rewrite reportgame function for changed database tables.<br>
Fixed issue of incorrect row being selected when you publish/unpublish with icons(tick/red circle) in administration list view
