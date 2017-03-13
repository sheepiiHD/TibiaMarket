			<div class="row">
				<div id="footer" class="col-xs-12">
					2016 &copy; TibiaMarket, All Rights Reserved | <a href="contact">Contact us</a> | <a href="staff">Staff Members</a> | <a href="tos">Terms of Service</a> |&nbsp;&nbsp; <a href="https://www.facebook.com/TibiaMarketOfficial/"> <i class="fa fa-facebook"></i> </a> &nbsp;&nbsp; | &nbsp; <a href="http://www.twitch.tv/sheepiiHD"><img src="img/twitch.png"></a> &nbsp; | &nbsp; <a href="ts3server://tibiamarket.teamspeak.ninja"><img src="img/teamspeak.png"></a> 
				</div>
			</div>

			<script src="js/jquery.min.js"></script>
			<script src="js/jquery-ui.custom.js"></script>
			<script src="js/bootstrap.min.js"></script>
			<script src="js/jquery.icheck.min.js"></script>
			<script src="js/select2.min.js"></script>
			<script src="js/jquery.dataTables.min.js"></script>
			<script data-rocketsrc="js/unicorn.chat.js" type="text/rocketscript" data-rocketoptimized="true"></script>
			<script src="js/jquery.nicescroll.min.js"></script>
			<script src="js/unicorn.js"></script>
			<script src="js/unicorn.tables.js"></script>
			<script type="text/javascript">
			//<![CDATA[
			try{if (!window.CloudFlare) {var CloudFlare=[{verbose:0,p:0,byc:0,owlid:"cf",bag2:1,mirage2:0,oracle:0,paths:{cloudflare:"nope"},atok:"nope",petok:"nope",zone:"bootstrap-hunter.com",rocket:"a",apps:{},sha2test:0}];document.write('<script type="text/javascript" src="//ajax.cloudflare.com/cdn-cgi/nexp/dok3v=0489c402f5/cloudflare.min.js"><'+'\/script>');}}catch(e){};
			//]]>
			</script>
			<script async="" data-rocketsrc="//www.google-analytics.com/analytics.js" data-rocketoptimized="true"></script>
			<script type="text/javascript" src="//ajax.cloudflare.com/cdn-cgi/nexp/dok3v=0489c402f5/cloudflare.min.js"></script>
			<script type="text/javascript"> 
			
				$(document).ready(function() {
					$("#specialdiv").css("display:none");
				});
				function hoverondiv(id, name, image, average_bid, total_auctions, description, rarity){
					if ( $(window).width() > 712) {
						$('#specialdiv').css("border-top", ".1px solid #ff0000;");
						$('#specialdiv').css("border-right", ".1px solid #ff0000;");
						$("#iteminfo_" + id).show(200);
						$("#specialdiv").append("<div id=\"iteminfo_" + id + "\" class=\"iteminfo\"> Please note the auctions may be incorrect.<br> <img alt=\"\" src=\"data:image/gif;base64," + image + "\" />  " + name + "<br><span style='color:green; text-decoration:bold;'></span>"+ description +"</div>");
					}
				}
				function hoveroffdiv(){
					$('#specialdiv').css("border", "none");
					$("#iteminfo_" + id).hide(200);
					$("#specialdiv").children("div").remove();
				}
				function getRanking(){
					
				}
			</script>
	</body>
</html>