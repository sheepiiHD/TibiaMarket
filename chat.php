<?php
include('._._inc_header._._.php');
if($TibiaMarket->isLogged() == false){
	$TibiaMarket->redirect("login");
}
$user = $TibiaMarket->userData();
?>
<script>
	function submitChat(){
		<?php
		if($TibiaMarket->isLogged() == false){
			$TibiaMarket->redirect("login");
		}
		$user = $TibiaMarket->userData();
		?>
		if(chat_form.msgbox.value == ''){
			return;
		}
		var username 		= "<?php echo $user['username']; ?>";
		var access_level 	= "<?php echo $user['access_level']; ?>";
		var message 		= chat_form.msgbox.value;
		var xmlhttp 		= new XMLHttpRequest();
		
		xmlhttp.onreadystatechange = function(){
			if(xmlhttp.readyState==4 && xmlhttp.status==200){
				var mydiv 	= document.getElementById("chat-messages-inner");
				
				mydiv.innerHTML = xmlhttp.responseText;
			}
		}
		xmlhttp.open('GET', 'php/insertchat.php?username='+username+'&access_level='+access_level+'&message='+message, true)
		xmlhttp.send();
	}
//$(document).ready(function(e){});
</script>
<div id="content">
	<div class="widget-box widget-chat">
		<div class="widget-title">
			<span class="icon">
				<i class="fa fa-comment"></i>
			</span>
			<h5>Open Chat</h5>
			<div class="buttons">
				<a class="btn go-full-screen"><i class="fa fa-resize-full"></i></a>
			</div>
		</div>
		<div class="widget-content nopadding">
			<div class="chat-content">
				<div class="chat-messages" id="chat-messages" style="overflow: hidden; outline: none;" tabindex="5000">
					<div id="chat-messages-inner" class="chat-messages-inner">
						<p id="msg-1" class="user-neytiri show" style="display: none;"><img src="img/chat/staff_icon.png" alt=""><span class="msg-block"><strong>Sheepyy</strong> <span class="msg">This is an admin test.</span></span></p>
						<p id="msg-2" class="user-cartoon-man show" style="display: none;"><img src="img/chat/user_icon.png" alt=""><span class="msg-block"><strong>Ceori</strong> <span class="time">- 18:46</span><span class="msg">This is a user test.</span></span></p>
					</div>
				</div>
				<div class="chat-message well">
					<form action="javascript:submitChat()" method="post" name="chat_form" class="form-horizontal">
						<span class="input-box input-group">
								<input placeholder="Enter message here..." type="text" class="form-control input-small" name="msgbox" id="msg-box" onkeydown = "if (event.keyCode == 13) document.getElementById('chatbutton').click()" >
								<span class="input-group-btn">
									<button id='chatbutton' class="btn btn-success btn-small" type="button" onclick="submitChat()">Send</button>
								</span>
							
						</span>
					</form>
				</div>
			</div>
			
		</div>
	</div>
</div>

<?php
include('._._inc_footer._._.php');
?>