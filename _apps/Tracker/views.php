<?

class View {
	
	public function showstats() {
		?>
<div id="logout">Log out</div> 
<section>
	<div class="usermenu">
		<div class="menuoption">&raquo;</div> 
		<h1>Sessions</h1> 
	</div> 
	<div class="useractivity">
		<section>
			<content class="info">
				<h1><span id="sessionid"></span><span class="meta"></span></h3> 
			</content> 
			<content class="resources"> 
				<div class="third">
					<h4 id="funds">spent: <span class="label"></label></h4>
					<h4 id="pc">spent: <span class="label"></label></h4>
					<h4 id="level">level: <span class="label"></span></h4> 
				</div>
				<div class="third"> 
					<h4 id="timer">playtime: <span class="label"></span></h4> 
					<h4 id="status">status: <span class="label"> </label></h4> 
					<h4 id="completed">completed: <span class="label"></span></h4> 
				</div> 
			</content> 
			<content class="game normalize">
				<h2>Screen Usage</h2> 
				<div class="uimenu"></div> 
				<div class="bottomnav"></div> 
				<div class="minimap"></div> 
			</content> 
			<content class="stats">
				<div class="maptype">
					<span class="option" id="points">View as points</span>
					<span class="option" id="heatmap">View as heatmap</span>
					<!-- <span class="option" id="cluster">View as clusters</span>  -->
				</div>
			</content> 
			<content>
				<div class="half">
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
				</div> 
				<div class="half">
					<h2>Panel Time</h2> 
					<div class="panel" id="on"> 
						<span class="label positive"></span>
					</div> 
					<div class="panel" id="off">
						<span class="label negative"></span> 
					</div> 
				</div>
			</content>
			<content class="rate normalize">
				<h2>Score Rate</h2> 
				<div class="half">
					<div class="data" id="environ">
						<span class="text">Avg. Environ Score: <span class="label positive" id="avg_environ_score"></span></span> 
						<span class="text">Avg. Environ Change: <span class="label positive" id="avg_environ_delta"></span></span>
					</div>
				</div> 
				<div class="half">
					<div class="data" id="economy">
						<span class="text">Avg. Economy Score: <span class="label negative" id="avg_economy_score">-</span></span> 
						<span class="text">Avg. Economy Change: <span class="label negative" id="avg_economy_delta"></span></span> 
					</div>
				</div> 
			</content>
			<content>
				<!-- environ score rate --> 
				<div class="score half" id="environ">
					<div class="graphplot"></div> 
				</div> 
				<!-- economy score rate --> 
				<div class="score half" id="economy">
					<div class="graphplot"></div> 
				</div> 

			</content> 
			<content>
				<h2>Node Rate <span class="meta">action/second</span></h2> 
				<div class="table">
					<div class="row">
						<div class="header fifth">ACTION</div> 
						<div class="header fifth">Bases</div> 
						<div class="header fifth">Upgrades</div> 
						<div class="header fifth">Events</div> 
						<div class="header fifth">Disasters</div> 
					</div> 
					<div class="row">
						<div class="datum fifth">Spawn</div> 
						<div class="header fifth" id="node00">-</div> 
						<div class="header fifth" id="node01"></div> 
						<div class="header fifth" id="node02"></div> 
						<div class="header fifth" id="node03"></div> 
					</div> 
					<div class="row">
						<div class="datum fifth">Unlock</div> 
						<div class="header fifth" id="node10"></div> 
						<div class="header fifth" id="node11"></div> 
						<div class="header fifth" id="node12">-</div> 
						<div class="header fifth" id="node13">-</div> 
					</div>
					<div class="row">
						<div class="datum fifth">Active</div> 
						<div class="header fifth" id="node20"></div> 
						<div class="header fifth" id="node21"></div> 
						<div class="header fifth" id="node22"></div> 
						<div class="header fifth" id="node23"></div> 
					</div>
					<div class="row">
						<div class="datum fifth">Responsive</div> 
						<div class="header fifth" id="node30"></div> 
						<div class="header fifth" id="node31"></div> 
						<div class="header fifth" id="node32"></div> 
						<div class="header fifth" id="node33"></div> 
					</div> 
				</div> 
			</content>
			<content class="region"> 
				<h2>Region Data</h2> 
				<div class="half">
					<h3>Particular</h3> 
					<div class="table">
						<div class="row">
							<div class="header fifth">REGION</div> 
							<div class="header fifth">Order</div> 
							<div class="header fifth">Avg Environ</div> 
							<div class="header fifth">Avg Economy</div> 
							<div class="header fifth">Focus</div> 
						</div> 
						<div class="row">
							<div class="header fifth">NA</div>  
							<div class="header fifth" id="region00"></div> 
							<div class="header fifth positive" id="region01"></div> 
							<div class="header fifth positive" id="region02"></div> 
							<div class="header fifth" id="region03"></div> 
						</div> 
						<div class="row">
							<div class="header fifth">SA</div>  
							<div class="header fifth" id="region10"></div> 
							<div class="header fifth positive" id="region11"></div> 
							<div class="header fifth positive" id="region12"></div> 
							<div class="header fifth" id="region13"></div> 
						</div>
						<div class="row">
							<div class="header fifth">EU</div>  
							<div class="header fifth" id="region20"></div> 
							<div class="header fifth positive" id="region21"></div> 
							<div class="header fifth positive" id="region22"></div> 
							<div class="header fifth" id="region23"></div> 
						</div>
						<div class="row">
							<div class="header fifth">AF</div>  
							<div class="header fifth" id="region30"></div> 
							<div class="header fifth positive" id="region31"></div> 
							<div class="header fifth negative" id="region32"></div> 
							<div class="header fifth" id="region33"></div> 
						</div> 
						<div class="row">
							<div class="header fifth">CA</div>  
							<div class="header fifth" id="region40"></div> 
							<div class="header fifth positive" id="region41"></div> 
							<div class="header fifth negative" id="region42"></div> 
							<div class="header fifth" id="region43"></div> 
						</div> 
						<div class="row">
							<div class="header fifth">EA</div>  
							<div class="header fifth" id="region50"></div> 
							<div class="header fifth negative" id="region51"></div> 
							<div class="header fifth positive" id="region52"></div> 
							<div class="header fifth" id="region53"></div> 
						</div> 
					</div> 
				</div> 
				<div class="half"> 
					<h3>General</h3> 
					<h4 id="funds">Unlock Rate: <span class="label"></label></h4> 
					<h4>Navigation</h4> 
					<div class="navigation">
						<div class="bargraph">
							<div class="bar">
								<div style="width:314px;" id="minimap"></div><span class="label" id="mini"></span>
								<div style="width:116px; left:334px;" id="regkeys"></div><span class="label" id="regk"style="left:365px"></span>
							</div>
						</div>
					</div>

				</div> 
			</content> 
		</section> 
	</div> 
</section>
		<?
	}
}

?>