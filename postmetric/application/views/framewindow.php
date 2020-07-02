<?php $this->load->view("site_header");?>
<div id="content"></div>
<iframe    src="<?php echo site_url("dreport");?>" width="1024" height="2000px" frameBorder="0" scrolling="no" onload="resizeIframe(this)">Browser not compatible.</iframe>
<script>
  function resizeIframe(obj) {
     obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
   obj.style.width = obj.contentWindow.document.body.scrollWidth + 'px';
    settop();
  }
</script>
<script>
function settop() { 
	 var elmnt = document.getElementById("content");
	elmnt.scrollIntoView();

	window.scrollTo(00, 0);
}
</script>


<?php $this->load->view("site_footer");?>
