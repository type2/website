<?php /* Smarty version 2.6.19, created on 2010-09-14 03:54:06
         compiled from sidebar-a.tpl */ ?>
<div id="content-right">


		<div class="content-right-in">
			<h2><a id = "searchtoggle" class="win-up" href="javascript:blindtoggle('search');toggleClass('searchtoggle','win-up','win-down');"><?php echo $this->_config[0]['vars']['search']; ?>
</a></h2>
			
			<form id = "search" method = "get" action = "managesearch.php" <?php echo ' onsubmit="return validateStandard(this,\'input_error\');"'; ?>
>
			<fieldset>
				<div class = "row">
					<input type="text" class = "text" id="query" name="query" />
				</div>
			
				<div id="choices"></div>
				<input type = "hidden" name = "action" value = "search" />
				
				<div id="indicator1" style="display:none;"><img src="templates/standard/images/symbols/indicator_arrows.gif" alt="<?php echo $this->_config[0]['vars']['searching']; ?>
" /></div>
				
				<button type="submit" title="<?php echo $this->_config[0]['vars']['gosearch']; ?>
"></button>
			</fieldset>

			</form>
	</div>
		
	
			

		
	<?php if ($this->_tpl_vars['showcloud'] == '1'): ?>
		<?php if ($this->_tpl_vars['cloud'] != ""): ?>
		<div class="content-right-in">	
			<h2><a id="tagcloudtoggle" class="win-up" href="javascript:blindtoggle('tagcloud');toggleClass('tagcloudtoggle','win-up','win-down');"><?php echo $this->_config[0]['vars']['tags']; ?>
</a></h2>
			<div id = "tagcloud" class="cloud">
				<?php echo $this->_tpl_vars['cloud']; ?>

			</div>
		</div>		
		<?php endif; ?>
	<?php endif; ?>
			
	
	
		<div class="content-right-in">		
			<h2><a id="onlinelisttoggle" class="win-up" href="javascript:blindtoggle('onlinelist');toggleClass('onlinelisttoggle','win-up','win-down');"><?php echo $this->_config[0]['vars']['usersonline']; ?>
</a></h2>

			<div id="onlinelist">
				<?php echo $this->_tpl_vars['cloud']; ?>

			</div>
	</div>	


		<?php echo '
			  <script type = "text/javascript">
			  new Ajax.Autocompleter(\'query\', \'choices\', \'managesearch.php?action=ajaxsearch\', {paramName:\'query\',minChars: 2,indicator: \'indicator1\'});
				 var on = new Ajax.PeriodicalUpdater("onlinelist","manageuser.php?action=onlinelist",{method:\'get\',evalScripts:true,frequency:35});
	
	
			</script>
		'; ?>


</div>