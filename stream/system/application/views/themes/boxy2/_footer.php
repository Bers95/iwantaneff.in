</div>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6/jquery.min.js"></script>
<script src="/stream/system/application/views/themes/boxy2/jquery.infinitescroll.js"></script>
<script src="http://platform.twitter.com/anywhere.js?id=AgQcHYQcayr3xGXq7Vfjcg&v=1"></script>
<script type="text/javascript">
function twt() {
	twttr.anywhere(function(T) {
		T('#activity_list').hovercards();
		T('.about').hovercards();
	});
};

$('#activity_list').infinitescroll({
	navSelector: "#pagination",
	nextSelector: "#pagination a:first",
	itemSelector: "#activity_list > li",
	loadingImg: "http://edanhewitt.com/load.gif",
	loadingText: "<em>loading more...</em>",
	loadingParent: "body",
	successCallback: function() {twt()}
});

twt();


$(function () {
   
$('a').each(function() {
   var a = new RegExp('/' + window.location.host + '/');
   if(!a.test(this.href)) {
       $(this).click(function(event) {
           event.preventDefault();
           event.stopPropagation();
           window.open(this.href, '_blank');
       });
   }
});



});
</script>
<script src="http://stats.higg.in/?js"></script>
</body>
</html>