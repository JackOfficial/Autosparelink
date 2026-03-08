<template>
    <Head title="Happy Family" />
    <!-- Start Preloader -->
    <!-- <Preloader /> -->
    <!-- End Preloader -->
      <Layout>
        <div class="hero-slider">
      <div class="slider-item th-fullpage hero-area" :style="{backgroundImage: `url('/storage/${header.photo}')`}">
        <div class="container">
          <div class="row">
            <div class="col-md-12 text-center">
              <h1 data-duration-in=".3" data-animation-in="fadeInUp" data-delay-in=".1">{{ header.header }}</h1>
              <p data-duration-in=".3" data-animation-in="fadeInUp" data-delay-in=".5">{{ header.text }}</p>
              <Link data-duration-in=".3" data-animation-in="fadeInUp" data-delay-in=".8" class="btn btn-main" :href="header.link">Explore Us</Link>
            </div>
          </div>
        </div>
      </div>
      <div class="slider-item th-fullpage hero-area d-none" style="background-image: url(Front/images/slider/slider-bg-2.jpg);">
        <div class="container">
          <div class="row">
            <div class="col-md-12 text-center">
              <h1 data-duration-in=".3" data-animation-in="fadeInDown" data-delay-in=".1">We Combine Design <br> and
                Creativity</h1>
              <p data-duration-in=".3" data-animation-in="fadeInDown" data-delay-in=".5">Create just what you need
                for your Perfect Website. Choose from a wide range
                <br> of Elements & simply put them on our Canvas.</p>
              <Link data-duration-in=".3" data-animation-in="fadeInDown" data-delay-in=".8"  class="btn btn-main" href="service.html">Explore Us</Link>
            </div>
          </div>
        </div>
      </div>
    </div>
<!-- Start About Section -->
<section class="service-2 section d-none">
  <div class="container">
    <div class="row">

      <div class="col-12">
        <!-- section title -->
        <div>
          <h2 class="title text-center">What We Offer</h2>
          <p><b>HAPPY FAMILY RWANDA ORGANIZATION (HFRO)</b> was born out of Passion to bridge the gap existed between the under privileged and vulnerable groups especially Girls, adolescent’s mothers , women and children in our society with right information; and create access to quality health care services, portable water, sound environment, Good governance and quality education, as well as fight injustices meted to these target groups in order to entrenched equal rights and privileges to all irrespective of age, sex, education, tribe and economic status etc.</p>
          <div class="border"></div>
        </div>
        <!-- /section title -->
      </div>

      <div class="col-md-4 text-center">
        <img src="Front/images/about/member.jpg" class="inline-block" alt="" />
      </div>
      <div class="col-md-8 text-center">
        <div class="row text-center">
          <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="service-item">
              <i class="tf-ion-ios-alarm-outline"></i>
              <h4>Education</h4>
              <p>We believe that education is the most powerful tool for breaking the cycle of poverty. We work to improve access to quality education for children in underserved areas. By building schools, providing learning materials, and offering scholarships, we aim to empower future generations with knowledge and skills, opening doors to better opportunities.</p>
            </div>
          </div><!-- END COL -->
          <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="service-item">
              <i class="tf-ion-ios-briefcase-outline"></i>
              <h4>Healthcare</h4>
              <p>Good health is fundamental to overall wellbeing and development. We focus on improving healthcare facilities in marginalized communities, providing medical equipment and medicines, and conducting health awareness campaigns. Our goal is to ensure that everyone has access to basic healthcare services and information to lead healthy lives.</p>
            </div>
          </div><!-- END COL -->
          <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="service-item">
              <i class="tf-ion-ios-email-outline"></i>
              <h4>Women Empowerment</h4>
              <p>We are committed to empowering women and promoting gender equality. Through various initiatives, we provide skill development programs, vocational training, and micro-financing opportunities to women in need. By enhancing their capabilities and supporting their entrepreneurial endeavors, we aim to create a more equitable society.</p>
            </div>
          </div><!-- END COL -->
          <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="service-item">
              <i class="tf-ion-ios-locked-outline"></i>
              <h4>Environmental Sustainability</h4>
              <p>We believe in protecting and preserving our planet for future generations. Our environmental initiatives include tree planting drives, waste management programs, and raising awareness about climate change and sustainable practices. We strive to promote eco-friendly habits and create a greener, more sustainable world.</p>
            </div>
          </div><!-- END COL -->
        </div>
		<a href="/causes" class="btn btn-main mt-20">Learn More</a>
      </div>
    </div> <!-- End row -->
  </div> <!-- End container -->
</section> <!-- End section -->

<!-- Start About Section -->
<section class="about-2 section" id="about">
	<div class="container">
		<div class="row">

			<!-- section title -->
			<div class="col-12">
				<div class="mb-5">
					<q class="text-center">Welcome to 
						<b>HAPPY FAMILY RWANDA ORGANIZATION (HFRO),</b> 
						we believe that every individual has the power to make a 
						difference. Whether it's through volunteering, fundraising, or spreading 
						awareness, your involvement and commitment will contribute to 
						transforming lives and bringing about sustainable change.</q>
						<span class="ml-2">Founder & Executive Director</span>
					<div class="border"></div>
				</div>
			</div>
			<!-- /section title -->

			<div class="col-md-6">
				<img src="Front/images/photos/img_5.jpg" class="img-fluid" alt="" />
			</div>
			<div class="col-md-6">
				<h2>Our Work</h2>m
				<ul class="checklist">
					<li v-for="cause in causes" :key="cause.id">{{ cause.cause }}</li>
				</ul>
				<Link href="/causes" class="btn btn-main mt-20">Read More</Link>
			</div>
		</div> <!-- End row -->
	</div> <!-- End container -->
</section> <!-- End section -->

	<!-- Start Services Section
		==================================== -->

<section class="services" id="services">
	<div class="container">
		<div class="row no-gutters">
			<!-- section title -->
			<div class="col-12">
				<div class="title text-center">
					<h2>Our Services</h2>
					<p>Vestibulum nisl tortor, consectetur quis imperdiet bibendum, laoreet sed arcu. Sed condimentum iaculis ex,
						in faucibus lorem accumsan non. Donec mattis tincidunt metus. Morbi sed tortor a risus luctus dignissim.</p>
					<div class="border"></div>
				</div>
			</div>
			<!-- /section title -->

			<!-- Single Service Item -->
			<div v-for="(cause, index) in causes" :key="cause.id" class="col-lg-4 col-sm-6 mb-4 mb-lg-0">
				<div :class="`service-block p-4 ${(index%2 !=0) ? 'color-bg':''} text-center`">
					<div class="service-icon text-center">
						<img :src="`/storage/${cause.photo}`" class="card-img-top w-100" :alt="cause.cause">
					</div>
					<h3>{{ cause.cause }}</h3>
					<p>{{ cause.description }}</p>
				</div>
			</div>

		</div> <!-- End row -->
	</div> <!-- End container -->
</section> <!-- End section -->

<!--
		Project Section
		=========================================== -->
		<section class="team-skills section-sm bg-light" id="skills">
	<div class="container">
		<div class="row">

			<!-- section title -->
			<div class="col-12">
				<div class="title text-center">
					<h2>Our Projects</h2>
					<p>At Happy Family Rwanda Organization (HFRO), we are passionate about addressing the challenges faced by underprivileged communities around the world. Through our various programs and initiatives, we strive to make a positive impact in the lives of individuals and promote sustainable change.</p>
					<div class="border"></div>
				</div>
			</div>
			<!-- /section title -->
			<div class="col-md-6 col-md-offset-1">
				<div class="progress-block">
					<ul>
						<li v-for="project in projects" :key="project.id">
							<a :href="`/project/${project.id}`" class="text-dark">
								<span>{{ project.project }}</span>
							<div class="progress">
								<div class="progress-bar" :style="`width: ${project.progress}%;`">
								</div>
							</div>
							</a>
						</li>
					</ul>
				</div>
				<Link href="/projects" class="btn btn-main mt-20">All Projects</Link>
			</div>
			<div class="col-md-5" v-if="projects.length>0">
				<div class="team-skills-content">
					<img :src="`/storage/${current_project.photo}`" :alt="current_project.project" class="img img-responsive w-100" />
				</div>
			</div>
		</div> <!-- End row -->
		<div class="row d-none">
			<div class="col-md-12">
				<div id="clients-slider" class="clients-logo-slider">
					<img src="Front/images/client-logo/logo1.png" alt="" />
					<img src="Front/images/client-logo/logo-2.png" alt="" />
					<img src="Front/images/client-logo/logo-3.png" alt="" />
					<img src="Front/images/client-logo/logo-4.png" alt="" />
					<img src="Front/images/client-logo/logo-5.png" alt="" />
					<img src="Front/images/client-logo/logo1.png" alt="" />
					<img src="Front/images/client-logo/logo-2.png" alt="" />
					<img src="Front/images/client-logo/logo-3.png" alt="" />
					<img src="Front/images/client-logo/logo-4.png" alt="" />
					<img src="Front/images/client-logo/logo-5.png" alt="" />
				</div>

			</div>
		</div>
	</div> <!-- End container -->
</section> 

<section class="team-skills section-sm mb-1">
	<div class="container">
		<div class="row">
			<!-- section title -->
			<div class="col-12">
				<div class="title text-center">
					<h2>Upcoming Events</h2>
					<p>At Happy Family Rwanda Organization (HFRO), We are excited to share with you the 
						upcoming events and activities that aim to make a positive impact in our community. 
						Join us in creating meaningful change and be part of these inspiring initiatives</p>
					<div class="border"></div>
				</div>
			</div>
			<div class="col-md-5">
				<div class="team-skills-content">
					<img src="/storage/images/events/XZPCo9vZuNXO6jjEX4XjNxDA8jgQ2C6xmxCCDBvX.jpg" class="img img-responsive w-100" />
				</div>
			</div>
			<div class="col-md-6 col-md-offset-1 mb-3">
				<div class="">
					<ul class="checklist">
						<li v-for="event in events" :key="event.id"><Link :href="`/events/${event.event}`" class="text-dark"><i class="fa fa-check"></i> {{ event.event }}</Link></li>
					</ul>
				</div>
				<Link href="/events" class="btn btn-main mt-20">All Events</Link>
			</div>
		</div> <!-- End row -->
		<div class="row d-none">
			<div class="col-md-12">
				<div id="clients-slider" class="clients-logo-slider">
					<img src="Front/images/client-logo/logo1.png" alt="" />
					<img src="Front/images/client-logo/logo-2.png" alt="" />
					<img src="Front/images/client-logo/logo-3.png" alt="" />
					<img src="Front/images/client-logo/logo-4.png" alt="" />
					<img src="Front/images/client-logo/logo-5.png" alt="" />
					<img src="Front/images/client-logo/logo1.png" alt="" />
					<img src="Front/images/client-logo/logo-2.png" alt="" />
					<img src="Front/images/client-logo/logo-3.png" alt="" />
					<img src="Front/images/client-logo/logo-4.png" alt="" />
					<img src="Front/images/client-logo/logo-5.png" alt="" />
				</div>

			</div>
		</div>
	</div> <!-- End container -->
</section> 

<!--
		Start partner Section
		==================================== -->
		<Partners :partners="partners" />
		  <!-- end section -->

		  <section class="">
	<div class="container">
		<div class="row" v-if="stories.length>0">

			<!-- section title -->
			<div class="col-12">
				<div class="title text-center ">
					<h2> Discover our <span class="color">impactful stories</span></h2>
					<div class="border"></div>
					<p class="">Find out how Happy Family is fostering resilience and making impact in the loacl communities.
						Join us in building brighter fututre together
					</p>
				</div>
			</div>
			<!-- /section title -->
			<!-- single blog post -->
			<Link :href="`/story/${story.id}`" class="col-md-4 col-sm-6 col-xs-12 clearfix" v-for="story in stories" :key="story.id">
			<article>
				<div class="post-item">
					<div class="media-wrapper">
						<img :src="`/storage/${story.photo}`" :alt="story.heading" class="img-fluid" />
					</div>

					<div class="content">
						<h6><Link :href="`/story/${story.id}`">
					{{ (story.heading.length < 125) ? story.heading : story.heading.substr(0, 125)+'...' }}
						</Link></h6>
					</div>
				</div>
			</article>
			</Link>
			
			<!-- /single blog post -->
		</div> <!-- end row -->
		<div class="text-center" v-else>
          Stories will be here 
		</div>
	</div> <!-- end container -->
</section> <!-- end section -->

<!-- Start Call To Action -->
<section class="call-to-action section">
	<div class="container">
		<div class="row">
			<div class="col-md-12 text-center">
				<h2>Together, we can make a difference.</h2>
				<p>Together, let's build a better future, empower those in need, and create a world where everyone can thrive.</p>
				<p>We cannot achieve our goals alone. We rely on the support and generosity of individuals, corporations, and foundations to continue our life-changing work. Your contributions, whether through financial donations, in-kind support, or volunteering, make a real difference in the lives of those we serve. Together, we can bring hope and transform communities, one step at a time.</p>
				<Link href="/volunteer" class="btn btn-main">Join Us</Link>
			</div>
		</div> 		<!-- End row -->
	</div>   	<!-- End container -->
</section>   <!-- End section -->


<section class="testimonial section mb-1">
	<div class="container">
		<div id="myCarousel" class="carousel slide" data-ride="carousel">
				<h2 class="text-decoration-underline mb-5">Our <b>Testimonials</b></h2>
				<!-- Carousel indicators -->
				<ol class="carousel-indicators">
					<li data-target="#myCarousel" data-slide-to="0" class="active"></li>
					<li data-target="#myCarousel" data-slide-to="1"></li>
					<li data-target="#myCarousel" data-slide-to="2"></li>
				</ol>   
				<!-- Wrapper for carousel items -->
				<div class="carousel-inner">
					<div class="carousel-item active">
						<div class="row">
							<div class="col-sm-6">
								<div class="testimonial">
									<p class="text-light">Being a part of Happy Family has been an incredible journey. 
										Their unwavering dedication to combating climate change and creating a sustainable future is truly inspiring. Through their initiatives and advocacy, they have made a tangible impact in our community and beyond. I am proud to be associated with such a passionate and effective organization that is making a real difference in the world.</p>
								</div>
								<div class="media">
									<img src="Front/images/client-logo/clients-1.jpg" class="mr-3" alt="">
									<div class="media-body">
										<div class="overview">
											<div class="name"><b>Paula Wilson</b></div>
											<div class="details">Media Analyst / SkyNet</div>
											<div class="star-rating">
												<ul class="list-inline">
													<li class="list-inline-item"><i class="fa fa-star"></i></li>
													<li class="list-inline-item"><i class="fa fa-star"></i></li>
													<li class="list-inline-item"><i class="fa fa-star"></i></li>
													<li class="list-inline-item"><i class="fa fa-star"></i></li>
													<li class="list-inline-item"><i class="fa fa-star-o"></i></li>
												</ul>
											</div>
										</div>										
									</div>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="testimonial">
									<p class="text-light">
										Happy Family has been a beacon of hope and support for survivors of gender-based violence. When I reached out to them, I was met with compassion, understanding, and resources that helped me navigate a difficult journey. The empathy and guidance provided by their dedicated team have empowered me to heal and reclaim my life. I am forever grateful for the tireless efforts of Happy Family in combating gender-based violence and creating a safer society for all.
									</p>
								</div>
								<div class="media">
									<img src="Front/images/client-logo/clients-2.jpg" class="mr-3" alt="" />
									<div class="media-body">
										<div class="overview">
											<div class="name"><b>Antonio Moreno</b></div>
											<div class="details">Web Developer / SoftBee</div>
											<div class="star-rating">
												<ul class="list-inline">
													<li class="list-inline-item"><i class="fa fa-star"></i></li>
													<li class="list-inline-item"><i class="fa fa-star"></i></li>
													<li class="list-inline-item"><i class="fa fa-star"></i></li>
													<li class="list-inline-item"><i class="fa fa-star"></i></li>
													<li class="list-inline-item"><i class="fa fa-star-o"></i></li>
												</ul>
											</div>
										</div>										
									</div>
								</div>
							</div>
						</div>			
					</div>
					<div class="carousel-item">
						<div class="row">
							<div class="col-sm-6">
								<div class="testimonial">
									<p class="text-light">I am incredibly grateful for the work that Happy Family does in our community. Their dedication to helping those in need is truly inspiring. Through their efforts, they have made a significant impact on the lives of countless individuals and families. Thank you for your commitment to making the world a better place.</p>
								</div>
								<div class="media">
									<img src="Front/images/client-logo/clients-3.jpg" class="mr-3" alt="" />
									<div class="media-body">
										<div class="overview">
											<div class="name"><b>Michael Holz</b></div>
											<div class="details">Web Developer / DevCorp</div>											
											<div class="star-rating">
												<ul class="list-inline">
													<li class="list-inline-item"><i class="fa fa-star"></i></li>
													<li class="list-inline-item"><i class="fa fa-star"></i></li>
													<li class="list-inline-item"><i class="fa fa-star"></i></li>
													<li class="list-inline-item"><i class="fa fa-star"></i></li>
													<li class="list-inline-item"><i class="fa fa-star-o"></i></li>
												</ul>
											</div>
										</div>										
									</div>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="testimonial">
									<p class="text-light">I have been a volunteer at Happy Family for several years, and it has been an incredibly rewarding experience. The organization's mission to empower underprivileged youth is close to my heart, and I have witnessed firsthand the positive transformation it brings to the lives of these children. I am proud to be a part of such a caring and impactful organization.</p>
								</div>
								<div class="media">
									<img src="Front/images/client-logo/clients-2.jpg" class="mr-3" alt="" />
									<div class="media-body">
										<div class="overview">
											<div class="name"><b>Mary Saveley</b></div>
											<div class="details">Graphic Designer / MarsMedia</div>
											<div class="star-rating">
												<ul class="list-inline">
													<li class="list-inline-item"><i class="fa fa-star"></i></li>
													<li class="list-inline-item"><i class="fa fa-star"></i></li>
													<li class="list-inline-item"><i class="fa fa-star"></i></li>
													<li class="list-inline-item"><i class="fa fa-star"></i></li>
													<li class="list-inline-item"><i class="fa fa-star-o"></i></li>
												</ul>
											</div>
										</div>										
									</div>
								</div>
							</div>
						</div>			
					</div>
					<div class="carousel-item">
						<div class="row">
							<div class="col-sm-6">
								<div class="testimonial">
									<p class="text-light">I would like to recommend Happy Family for their exceptional work in providing disaster relief. When our community faced a devastating natural disaster, their quick response, and organized efforts made a significant difference. They efficiently distributed supplies, offered support, and helped rebuild homes. Their commitment to serving in times of crisis is recommendable.</p>
								</div>
								<div class="media">
									<img src="Front/images/client-logo/clients-1.jpg" class="mr-3" alt="" />
									<div class="media-body">
										<div class="overview">
											<div class="name"><b>Martin Sommer</b></div>
											<div class="details">SEO Analyst / RealSearch</div>
											<div class="star-rating">
												<ul class="list-inline">
													<li class="list-inline-item"><i class="fa fa-star"></i></li>
													<li class="list-inline-item"><i class="fa fa-star"></i></li>
													<li class="list-inline-item"><i class="fa fa-star"></i></li>
													<li class="list-inline-item"><i class="fa fa-star"></i></li>
													<li class="list-inline-item"><i class="fa fa-star-o"></i></li>
												</ul>
											</div>
										</div>										
									</div>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="testimonial">
									<p class="text-light">
										Being a recipient of Happy Family support and assistance was a life-changing experience for me. They provided me with the necessary resources and guidance to overcome personal challenges and rebuild my life. Their compassion, empathy, and non-judgmental approach created a safe space for me to heal and grow. I am forever grateful for their unwavering support.
									</p>
								</div>
								<div class="media">
									<div class="media-left d-flex mr-3">
										<img src="Front/images/client-logo/clients-2.jpg" alt="" />										
									</div>
									<div class="media-body">
										<div class="overview">
											<div class="name"><b>John Williams</b></div>
											<div class="details">Web Designer / UniqueDesign</div>
											<div class="star-rating">
												<ul class="list-inline">
													<li class="list-inline-item"><i class="fa fa-star"></i></li>
													<li class="list-inline-item"><i class="fa fa-star"></i></li>
													<li class="list-inline-item"><i class="fa fa-star"></i></li>
													<li class="list-inline-item"><i class="fa fa-star"></i></li>
													<li class="list-inline-item"><i class="fa fa-star-o"></i></li>
												</ul>
											</div>
										</div>										
									</div>
								</div>
							</div>
						</div>			
					</div>
				</div>
				<!-- Carousel controls -->
				<Link class="carousel-control-prev" href="#myCarousel" data-slide="prev">
					<i class="fa fa-chevron-left"></i>
				</Link>
				<Link class="carousel-control-next" href="#myCarousel" data-slide="next">
					<i class="fa fa-chevron-right"></i>
				</Link>
			</div>
	</div>
</section>

<section class="blog mb-1" id="blog">
	<div class="container">
		<div class="row">

			<!-- section title -->
			<div class="col-12">
				<div class="title text-center ">
					<h2> Latest <span class="color">Posts</span></h2>
					<div class="border"></div>
					<p class="d-none">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ducimus facere accusamus, reprehenderit libero
						inventore nam.</p>
				</div>
			</div>
			<!-- /section title -->
			<!-- single blog post -->
			<article class="col-md-4 col-sm-6 col-xs-12 clearfix " v-for="blog in blogs" :key="blog.id">
				<div class="post-item">
					<div class="media-wrapper">
						<img :src="`/storage/${blog.photo}`" :alt="blog.title" class="img-fluid" />
					</div>

					<div class="content">
						<h3><Link :href="`/blog/${blog.title}`">{{ blog.title }}</Link></h3>
						<div v-html="blog.content.substr(0, 250)"></div>
						<Link class="btn btn-main" :href="`/blog/${blog.title}`">Read more</Link>
					</div>
				</div>
			</article>
			<!-- /single blog post -->
		</div> <!-- end row -->
	</div> <!-- end container -->
</section> <!-- end section -->

    <!-- end Footer Area
    ========================================== -->
      </Layout>
  </template>

<script>
import { Head, Link } from '@inertiajs/vue3';
import Layout from "../Shared/Layout.vue";
import Preloader from "../Shared/Preloader.vue";
import Partners from "./OtherComponents/Partners.vue";
// defineProps({ name: Object })
export default{
  props: { blogs: Object, projects: Object, causes: Object, partners: Object, 
	events: Object, stories: Object, header: Object, current_project: Object },
  components: { Layout, Preloader, Head, Link, Partners },
    methods: { },
  data(){
    return {}
  },
  computed:{
	computedClass(){
		return{
			'service-block': true,
			 'p-4': true,
			 'color-bg': true,
			 'text-center': true
		}
	}
  }
}
</script>