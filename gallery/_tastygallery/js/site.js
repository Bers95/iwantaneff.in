var jsonlock,cont_w,perrow,ratio,data,linkage,range_support=false,showall=false,sliderobject;

// default images per row (only displayed initially, because we're using local storage to remember what each user prefers)
perrow=3;
// thumbnail ratio
ratio=0.6;

/*//////////////////////////// end of user-configurable options ////////////////////////////*/

// container width
cont_w=$("#images").width();

// local storage stuff
if(Modernizr.localstorage){
	perrow=localStorage.perrow ? localStorage.perrow : perrow;
}
// range stuff
if(Modernizr.inputtypes.range){
	$("#slider").addClass('html5');
	sliderobject=$("#slider input");
	sliderobject.val(perrow).show();
	sliderobject.bind('mouseup',function(){
		imageSize($(this).val());
	});
}else{
	$("#slider").addClass('jq');
	sliderobject=$("#slider_jq");
	sliderobject.show().slider({
		'min' : 3,
		'max' : 10,
		change : function(){
			imageSize($(this).slider("option","value"));
		}
	}).slider('value',perrow);
}

function urlencode (str) {
    str = (str+'').toString();
    return encodeURIComponent(str).replace(/!/g, '%21').replace(/'/g, '%27').replace(/\(/g, '%28').replace(/\)/g, '%29').replace(/\*/g, '%2A')/*.replace(/%2F/g, '/')*/;
}
function urldecode (str) {   
    return decodeURIComponent(str.replace(/\+/g, '%20'));
}

function obIsEmpty(o) {
	for(var i in o){ return false; }
	return true;
}

function imageSize(perrow){
	var imgwidth=(cont_w/perrow)-8;
	var twidth=Math.floor(imgwidth-6);
	var theight=Math.floor((imgwidth*ratio)-6);
	if(Modernizr.csstransitions){
		$("#images .image > a > img").css({
			'width':twidth+'px',
			'height':theight+'px'
		});
	}else{
		$("#images .image > a > img").animate({
			'width':twidth+'px',
			'height':theight+'px'
		},600);
	}
	$("#slider_n").html(perrow);
	if(Modernizr.localstorage){
		localStorage.perrow=perrow;
	}
}

function showallbars(mmm){
	if(mmm){
		$('.bar').css('opacity','0.9');
		showall=true;
	}else{
		$('.bar').css('opacity','0');
		showall=false;
	}
}

function getImages(dir){
	var totalhref='', pathhtml, firstpart=false, firstdir=false;
	dir=urldecode(dir);
	$("#images").slideUp('slow',function(){
		$("#images").empty();
		jsonlock=Math.floor(Math.random()*99999);
		$.getJSON("_tastygallery/do.php?d="+urlencode(dir)+"&lock="+jsonlock+"&cb=?", function(data){
			if($(".warning.w_empty").length){
				$(".warning.w_empty").hide();
			}
			if(jsonlock == data.jsonlock){
				if(data.status==0){
					for(i in data.cdirs){
						totalhref+=data.cdirs[i];
						pathhtml='<div class="pathpart"><a id="path'+i+'" href="'+'#'+urlencode(totalhref)+'">'+data.cdirs[i]+'<div class="breadcrumb"></div></a></div>';
						if(firstpart){
							$("#paths").append(pathhtml);
						}else{
							$("#paths").html(pathhtml);
						}
						firstpart=true;
					}
					if(!obIsEmpty(data.dirs)){
						$("#paths").append('<div id="path_go"><a href="javascript:;"><img src="_tastygallery/images/folder_go.png"></a><div id="path_more"></div></div>');
						$("#path_go").click(function(){
							$("#path_more").animate({height: 'toggle'},600);
						});
						for(i in data.dirs){
							dirhtml='<a class="dir" href="'+((document.location.hash.length > 0) ? document.location.hash : '#')+data.dirs[i]+'"><img src="_tastygallery/images/folder.png">'+data.dirs[i]+'</a>';
							if(firstdir){
								$("#path_more").append(dirhtml);
							}else{
								$("#path_more").html(dirhtml);
							}
							firstdir=true;
						}
					}
					if(!obIsEmpty(data.images)){
						for(i in data.images){
							$("#images").append(
								'<div class="image" id="img'+i+'">'+
									'<a href="javascript:;" class="uhh"><img src="_tastygallery/img.php?i='+data.cdir+urlencode(data.images[i].n)+'&w='+Math.floor(((cont_w/3)-8)-6)+'&h='+Math.floor((((cont_w/3)-8)*ratio)-6)+'&t='+data.images[i].t+'"></a>'+
									'<div class="bar clearfix">'+
										'<span class="name">'+
											data.images[i].n+
										'</span>'+
										'<div class="controls">'+
											'<a href="javascript:;"><img src="_tastygallery/images/image.png"></a>'+
										'</div>'+
									'</div>'+
								'</div>'
							);
							var fboxw=(data.images[i].w > 800) ? 800 : data.images[i].w;
							var fboxh=Math.floor(data.images[i].h/(data.images[i].w/fboxw));
							$('#img'+i+' a.uhh').fancybox({
								autoScale: false,
								autoDimensions: false,
								width: fboxw,
								height: fboxh,
								content: '<img src="_tastygallery/images/loading.gif" style="z-index:1500;position:absolute;top:'+fboxh/2+'px;left:'+fboxw/2+'px;"><img style="position:absolute;top:0;left:0;z-index:1600;" src="_tastygallery/img.php?i='+data.cdir+urlencode(data.images[i].n)+'&w='+fboxw+'">'
							});
							$('#img'+i).data({
								'name':data.images[i].n,
								'dir':data.cdir,
								'width':data.images[i].w,
								'height':data.images[i].h,
								'time':data.images[i].t
							});
							$('#img'+i+' .bar .controls a img').tipsy({
								delayOut: 150,
								fade: true, 
								gravity: 's',
								opacity: 0.9,
								html: true,
								offset: 9,
								title: function(){
									var width,height,dir,name,i,time;
									i=$(this).parents('.image').attr('id').substr(3);
									dir=$(this).parents('.image').data('dir');
									name=$(this).parents('.image').data('name');
									width=$(this).parents('.image').data('width');
									height=$(this).parents('.image').data('height');
									time=$(this).parents('.image').data('time');
									var rt=''+
									'<div class="sizes clearfix">'+
										'<a class="size ssmall" href="javascript:;" onclick="linkage(this,'+i+','+Math.floor(width/4)+','+Math.floor(height/4)+');">'+
											'<strong>Small</strong><br>'+Math.floor(width/4)+' x '+Math.floor(height/4)+''+
										'</a>'+
										'<a class="size smedium" href="javascript:;" onclick="linkage(this,'+i+','+Math.floor(width/3)+','+Math.floor(height/3)+');">'+
											'<strong>Medium</strong><br>'+Math.floor(width/3)+' x '+Math.floor(height/3)+''+
										'</a>'+
										'<a class="size slarge" href="javascript:;" onclick="linkage(this,'+i+','+Math.floor(width/2)+','+Math.floor(height/2)+');">'+
											'<strong>Large</strong><br>'+Math.floor(width/2)+' x '+Math.floor(height/2)+''+
										'</a>'+
										'<a class="size sthumb" href="javascript:;" onclick="linkage(this,'+i+',300,180);">'+
											'<strong>Thumb</strong><br>300 x 180'+
										'</a>'+
										'<a class="size soriginal" href="javascript:;" onclick="linkage(this,'+i+','+width+','+height+');">'+
											'<strong>Original</strong><br>'+width+' x '+height+''+
										'</a>'+
										'<br><div class="linkage"><input type="text"></div>'+
									'</div>';
									return rt;
								}
							});
							$('#img'+i+' .bar').data('hover',false).hover(
								function(){ $(this).data('hover',true); },
								function(){ $(this).data('hover',false); }
							);
							$('#img'+i).hover(
								function(){ if(!showall){ $(this).children('.bar').css('opacity','0.9'); } },
								function(){
									if(!showall){
										var that=this;
										setTimeout(function(){
											if(!($(that).children('.bar').children('.controls').children('a').children('img').tipsy('isUp')) && !($(that).children('.bar').data('hover'))){
												$(that).children('.bar').css('opacity','0');
											}
										},250);
									}
								}
							);
						}
						if(data.thumb==1){
							//$("#messages").append('<div class="warning w_caching">Please wait while thumbnails are being cached...</div>');
							//setTimeout(function(){ $(".warning.w_caching").fadeOut(1000); },3000);
						}else if(data.thumb==2){
							$("#messages").append('<div class="warning w_nothumb">Couldn\'t create thumbnail folder. Please create a <span style="color:white;">'+t_thumbdir+'</span> folder in <span style="color:white;">'+((data.cdir.length > 0) ? data.cdir : "the installation folder")+'</span>, and chmod it to 755 (or, if that doesn\'t work, 777).</div>');
						}
					}else{
						$("#messages").append('<div class="warning w_empty">Whoops, this folder\'s empty! Stick some of your favorite images in to start the action.</div>');
					}
					if($("#slider").hasClass('html5')){
						$("#images").slideDown('slow');
					}
					imageSize( ($("#slider").hasClass('html5')) ? sliderobject.val() : sliderobject.slider("option","value") );
					if($("#slider").hasClass('jq')){
						$("#images").show();
					}
				}else if(data.status==100){
					$("#messages").append('<div class="warning w_denied">Access denied.</div>');
				}else{
					$("#messages").append('<div class="warning w_denied">Sorry, but an error (code '+data.status+') occured.</div>');
				}
			}
		});
	});
}

function linkage(that,i,w,h){
	var image;
	image=$("#img"+i);
	if(w===undefined || w==image.data('width')){ w=''; }
	if(h===undefined || h==image.data('height')){ h=''; }
	$(that).parent().children('.linkage').children('input').val(t_dn+'_tastygallery/img.php?i='+image.data('dir')+image.data('name')+'&w='+w+'&h='+h+'&t='+image.data('time')).parent().show();
}

$(document).ready(function(){
	$('#topinfo').tipsy({
		delayOut: 250,
		fade: true, 
		gravity: 'ne',
		opacity: 0.8,
		offset: 6,
		html: true,
		id: 'topinfo_tip',
		title: function(){
			var rt=
			'<table id="topinfo_table">'+
				'<tr>'+
					'<td><strong>Version</strong><span class="hackyspacer"></span></td>'+
					'<td><abbr title="build '+t_subversion+'">'+t_version+'</abbr></td>'+
				'</tr>'+
				'<tr>'+
					'<td><strong>Info</strong></td>'+
					'<td><a href="http://tastydev.net/#tastygallery" title="tastygallery info" class="softlink">tastydev.net</a></td>'+
				'</tr>'+
				'<tr>'+
					'<td><strong>Copyright</strong></td>'+
					'<td><a href="http://vladh.net/" title="vladh.net" class="softlink">Vlad Harbuz</a></td>'+
				'</tr>'+
			'</table>';
			return rt;
		}
	});
	if(document.location.hash.length > 0){
		getImages(document.location.hash.substr(1));
	}else{
		getImages('');
	}
	$(window).bind("hashchange",function(e){
		getImages(document.location.hash.substr(1));
	});
});

























