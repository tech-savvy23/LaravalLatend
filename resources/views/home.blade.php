
<!DOCTYPE HTML>
<html>
<head>
<title>Safetifyme | Electrical & Fire safety audit</title>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="{{asset('css/app.css')}}" rel="stylesheet" type="text/css" media="all" />
<script src="{{asset('js/app.js')}}" type="text/javascript"></script>
	<script type="text/javascript">
		jQuery(document).ready(function($) {
			$(".scroll").click(function(event){		
				event.preventDefault();
				$('html,body').animate({scrollTop:$(this.hash).offset().top},1200);
			});
		});
	</script>
    <script type="text/javascript">
    	$(document).ready(function() {
		    $('.single-item').slick({
		        dots: true,
		        infinite: true,
		        speed: 300,
		        autoplay:true,
		        arrows:false,
		        slidesToShow: 1,
		        slidesToScroll: 1
		     });
       });
    </script>
</head>
<body>
   <div class="header">	
    <div class="header-top">
       <div class="wrap wrap-header"> 
	         <div class="logo">
				<a href="index.html"><img src="{{asset('images/safetifyme-front.jpg')}}" alt="" style="
    width: 100px;
"/></a>
			 </div>
			 <div class="cssmenu">
				<ul id="nav">
					 <li class="current"><a href="#section-1" class="scroll" >Home</a></li>
					 <li><a href="#section-2" class="scroll">Features</a></li>
					  
					 <li><a href="#section-4" class="scroll">About</a></li>
				     <li><a href="#section-5" class="scroll">Contact</a></li>
				</ul>
		    </div>
		    <div class="clear"></div>
	   </div>
	 </div>
	        <div class="header-bottom" id="section-1">
				<div class="wrap">
					<div class="img-banner">
						<div class="img-banner-info">
							<h1><span>Electrical & Fire safety audit</span></h1>
							<h3>SafetifyMe is a mobile location based app that combines the power of technology with real experts at your doorstep to find out the loopholes in your  electrical and fire prone assets at your home, shop, office & events</h3>
							
							<div class="app-btn"><a target="_blank" href="https://apps.apple.com/us/app/safetifyme/id1494088078#?platform=iphone">Download on Appstore</a></div>
						    </div>
						<div class="img-banner-pic">
							 <img src="{{asset('images/mobile.png')}}" align="" />
						   </div>
						<div class="clear"> </div>
					</div>
   				</div>
  			</div>
 		</div>
	 <div class="main">
	 	<!-- Features -->
	    <div class="features" id="section-2">
	       <div class="wrap">
		        <h2>How it Work?</h2>
				
				<div class="features-list">
				  <div class="col_1_of_3 span_1_of_3">
					<div class="dc-head">
						<div class="dc-head-icon">
							<img src="{{asset('images/features-img1.png')}}" alt="" />
						</div>
						<div class="dc-head-info">
							<h3>Book Vendor Instantly</h3></br>
							<p>Safetifyme app is your one touch vendor finding solution for your daily needs.</p>
						</div>
						<div class="clear"> </div>
					</div>
				  </div>
				 <div class="col_1_of_3 span_1_of_3">
					<div class="dc-head">
						<div class="dc-head-icon">
							<img src="{{asset('images/features-img2.png')}}" alt="" />
						</div>
						<div class="dc-head-info">
							<h3>Schedule your vendor according to your time.</h3>
							<p>Our Experts will be on your door step just in just one click booking.</p>
						</div>
						<div class="clear"> </div>
					</div>
				  </div>
				  <div class="col_1_of_3 span_1_of_3">
					<div class="dc-head">
						<div class="dc-head-icon">
							<img src="{{asset('images/features-img3.png')}}" alt="" />
						</div>
						<div class="dc-head-info">
							<h3>Cash and other payment options are available.</h3>
							<p>We have online payment option along with the cash in hand.</p>
						</div>
						<div class="clear"> </div>
					</div>
				  </div>
				<div class="clear"></div>
			</div>			
		   </div>
		 </div>   
		  <!-- Download Button  -->
		   
			<!-- Testimonials -->
			<h1 style="text-align:center; font-size:36px; font-weight:bold"></h1>
			<div style="padding:2% 5%;">
				<div>
					<iframe width="1220" height="500" src="https://www.youtube.com/embed/lEAK8ISXsjo" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
			   </div>
			</div>
		           {{-- <div class="testimonials" id="section-4">
			     		<div class="wrap">
			     			  <div class="slider single-item">
								<div>
									<iframe width="1220" height="500" src="https://www.youtube.com/embed/p3m9mt9IovA" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
								</div>
								<div>
									<iframe width="1220" height="500" src="https://www.youtube.com/embed/lEAK8ISXsjo" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
							   </div>
							
							</div>	 			
					</div>			      	 --}}
			   </div>
	     </div>
	   <!-- End Main -->
	   
	   <!-- Footer -->
         <div class="footer" id="section-5">
    	   <div class="wrap">
              <div class="footer-top">
              	<img src="{{asset('images/safetifyme-front.jpg')}}" alt=""  style="width: 150px;" />
       	       <div class="section group">
				<div class="col_1_of_3 span_1_of_3">					
					<h3>Important Links</h3>
					<ul>
						<li><a href="https://safetifyme.com/privacy-policy">
							
									<p>Privacy Policy</p>
								
							</a>											
						</li>
						<li>
							<a href="https://safetifyme.com/terms">
							
									<p>Tearms & Conditions</p>
								
							
							</a>											
						</li>
						<li>
							<a href="https://safetifyme.com/how-it-work">
							
									<p>How it Works</p>
																				
						</li>
						<li>
							<a href="https://safetifyme.com/faq">
							
									<p>F.A.Q</p>
							</a>												
						</li>
					</ul>
				</div>
				<div class="col_1_of_3 span_1_of_3">
					
				</div>
				 <div class="col_1_of_3 span_1_of_3">
					<h3>Helpline NO</h3>
					<h3><span>1800 572 0780</h3>
				</div>
			  </div>
            </div> 
         </div>    
          <div class="footer-bottom">
            <div class="copy">
		      <p> © All Rights Reserved 2020  by  <a href="https://perfecttesthouse.com/" target="_blank">Perfect Test house</a> </p>
	       </div>	    
	     </div>   
     </div>
       <script type="text/javascript">
			$(document).ready(function() {
				$().UItoTop({ easingType: 'easeOutQuart' });
				
			});
		</script>
       <a href="#" id="toTop"> <span id="toTopHover"></span> </a>
  </body>
</html>

    	
    	
            