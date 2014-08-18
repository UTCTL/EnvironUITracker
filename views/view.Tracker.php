<?
if(!isset($_SESSION['DESlogged']) || $_SESSION['DESlogged']!=1) 
  header("Location: login"); 

$page = 'tracker'; 
HTMLhead($page); 
HTMLnav($page); 
?>


<section class="vis">
	<div class="visnav">
		<div class="hidemenuoption">&raquo;</div> 
		<div class="subnav">
			<h1>Session Tracker</h1> 
			<ul class="menuoptions"> </ul> 
		</div>
	</div> 
	<div class="activity"> 
		<section>
			<content class="info clear" ctype="info">
				<h6>Session id: <br><span id="sessionid">##########</span></h6> 
			</content> 



			<content class="meta clear fourth" ctype="meta"> 
				<div id="age"><b>19</b> years old</div>
				<div id="gender"><b>female</b></div>
				<div id="ethnic"><b>white</b></div> 
			</content> 
			<content class="meta clear fourth" ctype="meta"> 
				<div id="gamer_no"><b>non-gamer</b></div> 
				<div id="ipaddr"><b>195.12.13.221</b></div>
				<div id="iploc"><b>City, CTR</b></div>
			</content> 
			<content class="meta clear fourth" ctype="details"> 
				<div id="funds"><b>$12345</b> spent</div>
				<div id="pc"><b>54321pc</b> spent</div>
				<div id="level">level <b>12</b></div> 
			</content> 
			<content class="meta clear fourth" ctype="details"> 
				<div id="time"><b>00:20:34</b> played</div> 
				<div id="status"><b><span class="chosen">won</span> | lost | quit </b></div> 
				<div id="completed"><b>83%</b> completed</div> 
			</content> 



			<content class="game box" ctype="game">
				<h2>Screen Usage</h2> 
				<div class="grid"></div> 
				<div class="frame">
					<div class="maptype">
						<span class="option" id="points">View as points</span>
						<span class="option" id="heatmap">View as heatmap</span> 
					</div>
				</div> 
			</content> 
			<content class="half box" ctype="cammov">
				<h2>Camera Movement</h2>
				<div class="movement" id="drag">
					<div class="icon"></div>
					<div class="bargraph">
						<div class="bar"><div class="model"></div><span class="label"></span></div>
					</div>
				</div>
				<div class="movement" id="arrows">
					<div class="icon"></div>
					<div class="bargraph">
						<div class="bar"><div class="model"></div><span class="label"></span></div>
					</div>
				</div>
				<div class="movement" id="keys">
					<div class="icon"></div>
					<div class="bargraph">
						<div class="bar"><div class="model"></div><span class="label"></span></div>
					</div>
				</div>
			</content>



			<content class="half box" ctype="panel">
				<h2>Panel Time</h2> 
				<div class="panel" id="on"> 
					<span class="label positive">0%</span>
				</div> 
				<div class="panel" id="off">
					<span class="label negative">0%</span> 
				</div> 
			</content>



			<content class="half box" ctype="navigation">
				<h2>Navigation</h2> 
				<div class="navigation">
					<div class="bargraph">
						<div class="bar">
							<div style="width:314px;" id="minimap"></div><span class="label" id="mini">Minimap 72%</span>
							<div style="width:116px; left:334px;" id="regkeys"></div><span class="label" id="regk"style="left:345px">Keys 18%</span>
						</div>
					</div>
				</div>
			</content> 



			<content class="divider" ctype="divider">
				<hr>
				<div class="regionbuttons">
					<div class="button selected" id="w"></div> 
					<div class="button" id="na"></div> 
					<div class="button" id="sa"></div> 
					<div class="button" id="eu"></div> 
					<div class="button" id="af"></div> 
					<div class="button" id="ca"></div> 
					<div class="button" id="ea"></div> 
				</div> 
			</content> 



			<content class="rate half box" id="environ" ctype="environrate">
				<h2><span class="label">W </span>Environ Score Rate</h2> 
				<!-- environ score rate --> 
				<div class="score" id="environ">
					<div class="graphplot"></div> 
				</div> 
				<div class="data" id="environ">
					<span class="text">Avg. Environ Score: <span class="label positive" id="avg_environ_score">65</span></span> 
					<span class="text">Avg. Environ Change: <span class="label positive" id="avg_environ_delta">+6.43</span></span>
				</div>
			</content>
			<content class="rate half box" id="economy" ctype="evonomyrate"> 
				<h2><span class="label">W </span>Economy Score Rate</h2> 
				<!-- economy score rate --> 
				<div class="score" id="economy">
					<div class="graphplot"></div> 
				</div> 
				<div class="data" id="economy">
					<span class="text">Avg. Economy Score: <span class="label negative" id="avg_economy_score">-12</span></span> 
					<span class="text">Avg. Economy Change: <span class="label negative" id="avg_economy_delta">-1.30</span></span> 
				</div> 
			</content> 



			<content class="regionfocus half box" ctype="regionfocus">
				<h2>Region focus</h2> 
				<div class="mapsmall"></div> 
				<div class="negative correlation">For this player...</div> 
			</content> 



			<content class="basefocus half box" ctype="basefocus">
				<h2>Bases focus</h2> 
				<div class="piechart"></div> 
				<div class="basenode"></div> 
			</content> 



			<content class="distribution box" ctype="distribution">
				<div class="dgui">
					<h2>Region</h2>
					<div class="meta" id="funds_small"><b>$863</b> spent</div> 
					<div class="meta" id="pc_small"><b>455</b> spent</div> 
					<div class="meta" id="focus_small"><b>23%</b> focus</div> 
					<br><br>
					<h3>Base Name</h3> 
					<ul class="upgrades">
						<li class="active">Upgrade 1</li> 
						<li class="active">Upgrade 2</li> 
						<li class="active">Upgrade 3</li> 
						<li class="active">Upgrade 4</li> 
						<li class="active">Upgrade 5</li> 
						<li class="inactive">Upgrade 6</li> 
						<li class="inactive">Upgrade 7</li> 
						<li class="inactive">Upgrade 8</li> 
						<li class="inactive">Upgrade 9</li> 
					</ul>
				</div> 
				<svg></svg>
			</content> 
			


			<content class="staticdivider" ctype="staticdivider">
				<hr>
				<div class="regionbuttons">
					<div class="button selected" id="w"></div> 
					<div class="button" id="na"></div> 
					<div class="button" id="sa"></div> 
					<div class="button" id="eu"></div> 
					<div class="button" id="af"></div> 
					<div class="button" id="ca"></div> 
					<div class="button" id="ea"></div> 
				</div> 
			</content> 
		</section> 
	</div> 
</section>


<div class="curtain">
	<a href="#" class="curtainClose"></a> 
	<div class="box"> 
		modify user content
	</div> 
</div> 


</body></html> 