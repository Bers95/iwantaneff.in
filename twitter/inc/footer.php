			</div>
			<div id="secondary">
				<?php echo displayMonths(); ?>
			</div></div>
		</div>
		<div id="footer">
			&copy; 2012 <a href="http://twitter.com/edanhewitt">David Higgins</a>, Made with love &#10084; by <a href="http://higg.in/">David Higgins</a>
		</div>
	</div>
<script src="http://stats.higg.in/?js"></script>
	</div>
</body>
</html>
<?php if($startTime){ echo "<!-- " . round((microtime(true) - $startTime), 5) . " s -->\n"; } ?>