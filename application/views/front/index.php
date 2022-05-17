<!doctype html>
<html lang="en">
<head>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/front/css/bootstrap.min.css" />
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/plugins/fontawesome-free/css/all.min.css" />
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/plugins/fontawesome-free/css/regular.min.css" />
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/front/css/style.css" />
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/front/css/font-awesome.min.css" />
	
	<title>Out Out - World New Social Platform To Earn Money.....Coming Soon!!!!!!!</title>

</head>

<body>

	<nav class="navbar navbar-light bg-light navbar-dark bg-dark">

		<div class="container-fluid">

			<div class="row align-items-center">

				<div class="col-8 col-md-3 col-lg-2 order-1">
					<a class="navbar-brand logo" href="<?php echo base_url(); ?>"> 
						<img class="img-fluid" src="<?php echo base_url(); ?>assets/front/image/logo.svg" alt=""> 
					</a>
				</div>

				<div class="col-12 col-md-6 col-lg-8 demo-txt text-center order-3 order-md-2 mt-2 mt-md-0">
					<a href="javascript:void(0);" class="header-text-marquee">
						<marquee>Top 5 places in Birmingham - Players Bar Birmingham, Resorts World Casino, Hollywood Bowl Wolverhampton, Cadbury World Bournville, Quinto Lounge</marquee>
					</a> 
					
					<h1 class="h4"> Upcoming Events</h1>
					<?php if ($this->session->flashdata('success')) { ?>
						<div class="alert alert-success alert-dismissible">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
						<h1 class="h4"> Success!</h1>
						<?php echo $this->session->flashdata('success'); ?>
						</div>
					<?php } ?>
					<?php if ($this->session->flashdata('error')) { ?>
						<div class="alert alert-danger alert-dismissible">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
						<h1 class="h4"> Alert!</h1>
						<?php echo $this->session->flashdata('error'); ?>
						</div>
					<?php } ?>
				</div>

				<div class="col-4 col-md-3 col-lg-2 text-right order-2 order-md-3"> 
					<img class="menubar" onclick="openNav()" src="<?php echo base_url(); ?>/assets/front/image/menu-bar.svg" width="40" alt="" /> 
				</div>

				<div id="mySidenav" class="sidenav"> 
					<a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a> 
					<a href="<?php echo base_url(); ?>company">Our Company</a>
					<a href="<?php echo base_url(); ?>business">Business</a>
					<a href="<?php echo base_url(); ?>customer">Customer</a>
					<a href="<?php echo base_url(); ?>support">Legal/Support</a> 
					<a href="<?php echo base_url(); ?>contact">Contact Us</a>
					<!--<a href="mailto:info@outout.app">info@outout.app</a>-->
					<!--<span style="font-size:15px;color:#818181;">Regarding advertising,promotions sponsorship or anything</span>-->
					<!--<p class="all-starts">It all starts Here!!!</p>-->
				</div>

				
			</div>
		</div>
	</nav>


	<div class="middle-part">
		<section class="py-4 p-xl-5">
		    <h1 class="h4 text-center">The number 1 social media/business app for everyone! </h1>
			<div class="container-fluid">
				<div class="row">
					<div class="col-12 col-lg text-white block-text">
						<div class="accordion-common">
						  <div class="card">
						    <div class="card-header" id="headingOne">
						        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
						          Businesses
						        </button>
						    </div>

						    <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
						      <div class="card-body">
						        OutOut is the best platform out there, (no pun intended) for businesses in the hospitality sector to promote, connect and get instant feedback from their customers. <strong>Sign up to start your free trial now!</strong>
						      </div>
						    </div>
						  </div>
						  <div class="card">
						    <div class="card-header" id="headingTwo">
						        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
						          Advertising packages / Extras
						        </button>
						    </div>
						    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
						      <div class="card-body">
						       Choose from our range of lucrative advertising packages to help your business gain maximum exposure like never before!<br/>
<strong>Bronze, Silver, Gold or Custom</strong> packages available.</br>
<strong>Extras</strong> - These extras allow your customers to use the app and communicate with your business more efficiently whilst also permitting you to use OutOut effectively to get feedback and information from your customers.  
</br>
Allow your customers to <strong>create events.</strong> 
</br>
Allow the business to receive <strong>notifications</strong> from outsiders and customers. 
</br>
Allow your customers to <strong>pre order/reserve.</strong> 
</br>
<strong>Check in,</strong> this allows the business to see how many people were in attendance.
						      </div>
						    </div>
						  </div>
						  <!--<div class="card">-->
						  <!--  <div class="card-header" id="headingThree">-->
						  <!--      <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">-->
						  <!--        Extras-->
						  <!--      </button>-->
						  <!--  </div>-->
						  <!--  <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">-->
						  <!--    <div class="card-body">-->
						  <!--      Create events notifications Pre ordering and reserving Check in-->
						  <!--    </div>-->
						  <!--  </div>-->
						  <!--</div>-->
						  <div class="card">
						    <div class="card-header" id="headingFour">
						        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
						         Sponsorships
						        </button>
						    </div>
						    <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion">
						      <div class="card-body">
						        Do you want OutOut to host an event at your business?<br/>
                                Get in touch with us so we can put you up on our website for upcoming events.
						      </div>
						    </div>
						  </div>
						  <div class="card">
						    <div class="card-header" id="headingFive">
						        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
						         Promotion 
						        </button>
						    </div>
						    <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordion">
						      <div class="card-body">
						        We would like to collaborate with popular fashion outlets to reward our customers and give them the best going OutOut outfit on us.
</br>If your an <strong>artist</strong> looking to promote your music to many different continents and demographics look no further. Contact <a href="mailto:info@outout.app">info@outout.app</a> for a collaboration to have your music advertised on our
app.
						      </div>
						    </div>
						  </div>
						  <div class="card">
						    <div class="card-header" id="headingSix">
						        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
						         Local suppliers
						        </button>
						    </div>
						    <div id="collapseSix" class="collapse" aria-labelledby="headingSix" data-parent="#accordion">
						      <div class="card-body">
						        We are actively looking to work with local suppliers in every region of the country to provide our wonderful OutOut customers with your services. If you are in the catering, event, or venue business, partner up with us today! Contact <a href="mailto:info@outout.app">info@outout.app</a> to get on board and become the main supplier in your region through our house party function.
						      </div>
						    </div>
						  </div>
						</div>

					</div>
					<div class="col-12 col-lg py-4 py-lg-0">
						<!-- <div id="myCarousel" class="carousel slide" data-ride="carousel">

							<ol class="carousel-indicators">
								<li data-target="#myCarousel" data-slide-to="0" class=""></li>
								<li data-target="#myCarousel" data-slide-to="1" class="active"></li>
								<li data-target="#myCarousel" data-slide-to="2"></li>
							</ol>

							<div class="carousel-inner">
								<div class="carousel-item text-center"> 
									<img class="first-slide img-fluid" src="<?php //echo base_url(); ?>assets/front/image/watch.svg" alt="First slide">
									<div class="carousel-caption">
										<p>Coming soon</p>
									</div>
								</div>

								<div class="carousel-item active text-center"> 
									<img class="second-slide img-fluid" src="<?php //echo base_url(); ?>assets/front/image/watch.svg" alt="Second slide">
									<div class="carousel-caption">
										<p>Coming soon</p>
									</div>

								</div>

								<div class="carousel-item text-center"> 
									<img class="third-slide img-fluid" src="<?php //echo base_url(); ?>assets/front/image/watch.svg" alt="Third slide">
									<div class="carousel-caption">
										<p>Coming soon</p>
									</div>
								</div>

							</div>

							<a class="carousel-control-prev" href="#myCarousel" role="button" data-slide="prev"> <span class="carousel-control-prev-icon" aria-hidden="true"></span> <span class="sr-only">Previous</span> </a>

							<a class="carousel-control-next" href="#myCarousel" role="button" data-slide="next"> <span class="carousel-control-next-icon" aria-hidden="true"></span> <span class="sr-only">Next</span> </a>

						</div> -->
						<iframe width="450" height="350" src="https://www.youtube.com/embed/5ee7gOXEnaU" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>

					</div>
					
					<div class="col-12 col-lg text-white block-text">
						<div class="accordion-common">
						  <div class="card">
						    <div class="card-header" id="headingSeven">
						        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseSeven" aria-expanded="true" aria-controls="collapseSeven">
						          Customers
						        </button>
						    </div>

						    <div id="collapseSeven" class="collapse" aria-labelledby="headingSeven" data-parent="#accordion">
						      <div class="card-body">
						        OutOut is all in the name, it’s about living for the now and capturing the special moments that matter. OutOut is your new best friend. It combines the two greatest elements of our life - social media + business. With OutOut your loyalty is rewarded every time you use the app. For once it’s all about you, the OutOut goers!
OutOut helps those who are attending university or college that are looking to connect with fellow students by using university mode available on the app. The app will also have recommendations of places near by you where you can go to eat, sleep, party and socialise. 
This one of a kind app isn’t just about partying, but certainly much more. One could be on a touristic holiday, attending a beach wedding, or having fun on the the golf course with their friends, it’s all about participating in an activity that gets you OutOut. On this platform you really are living in the moment.
						      </div>
						    </div>
						  </div>
						  <div class="card">
						    <div class="card-header" id="headingEight">
						        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
						         Why be on outOut?
						        </button>
						    </div>
						    <div id="collapseEight" class="collapse" aria-labelledby="headingEight" data-parent="#accordion">
						      <div class="card-body">
									Wherever you are, may that be a restaurant, arcade, pub or club now you can order directly from OutOut. This means you don’t have to leave your important chats nor do you have to get up to order, just simply look at the restaurant menu on our app and order what you like. You can even order food from home or pre order on the app from the business your planning to attend. On OutOut you no longer have to wait around in the cinema with the new OutOut order system.<br/> Great isn’t it!
						      </div>
						    </div>
						  </div>
						  <div class="card">
						    <div class="card-header" id="headingNine">
						        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseNine" aria-expanded="false" aria-controls="collapseNine">
						         Why is it different?
						        </button>
						    </div>
						    <div id="collapseNine" class="collapse" aria-labelledby="headingNine" data-parent="#accordion">
						      <div class="card-body">
								OutOut is unique in comparison to any other social media app. The new functions and our ethos sets us apart. This is not just another app, it’s the new way of life! No other social media app enables a person to achieve an income through throwing a party.<br/>
						        Your loyalty to businesses is rewarded through using the app your pictures/videos/stories collect points which equals rewards.<br/> These rewards can be provided by the business or OutOut itself with a premium account. Even if you don’t have a premium account you can still enjoy the app and have a friendly competition amongst your friends. OutOut was created with the thought of positive vibes only hence the first app to be created where you can comment only in gifts and emojis. This cool trendy app has also introduced new functions and filters to make it more fun than ever before especially when you’re OutOut. 
						      </div>
						    </div>
						  </div>
						  <div class="card">
						    <div class="card-header" id="headingTen">
						        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTen" aria-expanded="false" aria-controls="collapseTen">
						         How the points work?
						        </button>
						    </div>
						    <div id="collapseTen" class="collapse" aria-labelledby="headingTen" data-parent="#accordion">
						      <div class="card-body">
							    <p> By having a premium accounts these are some of the treats you can look forward to. 
Free tickets to concerts festivals based on your liking, free taxi rides, memberships on us at Gyms, Netflix or Amazon. 
OutOut allow you to earn money through the house-party section on our app. Yes that’s right you can even earn some £$€. If you are a fresher at university we will provide discount and host events on us which means you can save your money and spend it on your other need. These are just a few incentives but we have unlimited rewards planned as we believe our success is down to you our, OutOut goers.</p>
						      	Just remember <strong>points = rewards</strong>  
								<br/> 1 point for a night story
								<br/> 3 points each for every picture and video you upload
								<br/> 1 point gold star given by your friends
								<br/> 1 point for OutOut Outfit
								<br/> 1 point for all out message
								<br/> 3 points if you recommend OutOut
								<br/> 9 points if the person you recommended downloads premium
								<!-- <br/> Get access to <strong>business  rewards and OutOut giveaways for free.</strong>
								<br/> We will even try to get you a birthday message from your favourite celebrity as long as they are on OutOut. -->
								<br/> 
								<br/> Once you reach a certain milestone you can even set your own rewards. OutOut is nice like that!
						    </div>
						  </div>
						</div>				
					</div>
				</div>
				
			</div>
				
		</section>
		<section class="p-4 p-xl-5 text-center">
			<div class="container-fluid text-white">
				<h4 class="mb-0 px-xl-5" style="color:#ab006e;">
					<!--OutOut is the best platform out there no pun intended for business to promote,connect with customers and get the best feedback.
					Be on OutOut and get more than just a shoutout simples! -->
					Show your loyalty and you will be treated like royalty on OutOut!
				</h4>
			</div>
		</section>
		<section class="black-bg py-4 text-white">
			<div class="container-fluid">
				<div class="text-center px-xl-5">
				    <span class="bl-war">
					    <marquee>Post>Points>Prize>Positive Vibes</marquee> 
					</span>
					<h5>WARNING: Any hate or remarks that we believe to cause offence will be taken very seriously - by OutOut
					at least</h5>
				</div>
			</div>
		</section>
		<section class="p-4 pt-xl-5 text-center">
			<div class="container-fluid text-white">
				<h4 class="mb-4 pb-2" style="color:#ab006e;">
					<!-- OutOut is the number 1 app out there for businesses to promote,connect,and reward their customers. Customers show your loyalty and you will be treated like royalty with OutOut. -->
					Promote, connect, and reward your customers instantly on OutOut!
				</h4>
				
			</div>
		</section>
		<section class="pb-4 pb-xl-5 text-center text-white">
			<div class="container-fluid">
				<h3 style="color:#13004a;">Our Proud Sponsor</h3>
				<!--<p>ABYSS</p>-->
				<img class="img-fluid mt-3 sponsors-image" src="<?php echo base_url(); ?>/assets/front/image/abyss_logo.png" alt="Abyss Logo" height="250" width="550" />
			</div>
		</section>
	</div>

	<div class="footer">
		<div class="container-fluid">
			<div class="row">
				<div class="col-12 col-md-6 col-xl text-center text-md-left">
					<p>&copy; Copyrights <?php echo date('Y'); ?> OutOut All rights reserved.</p>
				</div>

				<div class="col-12 col-md-4 col-lg text-center">
					<p>IT ALL STARTS HERE!!!</p>
				</div>
				<div class="col-12 col-md-2 col-xl text-center text-md-right">
					<ul class="list-item">
						<li><a href="javascript:void(0);" target="_blank"><i class="fab fa-facebook-square"></i></a></li>
						<li><a href="https://www.linkedin.com/in/outout-app-514150209/" target="_blank"><i class="fab fa-linkedin"></i></a></li>
						<li><a href="https://twitter.com/OOut2021" target="_blank"><i class="fab fa-twitter-square"></i></a></li>
						<li><a href="https://www.instagram.com/outout2021/" target="_blank"><i class="fab fa-instagram-square"></i></i></a></li>
						<li><a href="javascript:void(0);" target="_blank"><i class="fab fa-youtube-square"></i></a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<div class="overlay"></div>
	<script src="<?php echo base_url(); ?>assets/front/js/jquery.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/front/js/bootstrap.min.js"></script>

<script>
	function openNav() {
		document.getElementById("mySidenav").style.width = "100%";
	}

	function closeNav() {
		document.getElementById("mySidenav").style.width = "0";
	}

</script>
</body>
</html>