<template>
<section class="team" id="team">
	<div class="container">
		<div class="row">
			<!-- section title -->
			<div class="col-12">
				<div class="title text-center ">
					<h2>Our Team</h2>
					<p class="d-none">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Doloremque quasi tempora obcaecati, quis
						similique quos.</p>
					
					<div class="border"></div>
				</div>
			</div>
		</div>
			<!-- /section title -->

			<!-- team member -->
			<div id="team" class="carousel slide" data-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active">
	  <div class="row">
		<div class="col-md-4 col-sm-6" v-for="team in ourTeam" :key="team.id">
			<div class="team-member text-center">
					<div class="member-photo">
						<!-- member photo -->
						<img class="img-fluid" :src="`/storage/${team.photo}`" :alt="team.name">
						<!-- /member photo -->

						<!-- member social profile -->
						<div class="mask" v-if="team.facebook != null || team.linkedin != null || team.twitter != null || team.email != null">
							<ul class="clearfix">
								<li v-if="team.linkedin != null"><a :href="team.linkedin" target="__blank"><i class="tf-ion-social-linkedin"></i></a></li>
								<li v-if="team.facebook != null"><a :href="team.facebook" target="__blank"><i class="tf-ion-social-facebook"></i></a></li>
								<li v-if="team.twitter != null"><a :href="team.twitter" target="__blank"><i class="tf-ion-social-twitter"></i></a></li>
								<li v-if="team.email != null"><a :href="`mailto:${team.email}`" target="__blank"><i class="tf-ion-social-google-outline"></i></a></li>
							</ul>
						</div>
						<!-- /member social profile -->
					</div>

					<!-- member name & designation -->
					<div class="member-content">
						<h3>{{ team.name }}</h3>
						<span>{{ team.title }}</span>
					</div>
					<!-- /member name & designation -->

				</div>
			</div>
	  </div>
    </div>
    <div class="carousel-item">
		<div class="row">
			<div class="col-md-4 col-sm-6" v-for="team in ourTeam" :key="team.id">
			<div class="team-member text-center">
					<div class="member-photo">
						<!-- member photo -->
						<img class="img-fluid" :src="`/storage/${team.photo}`" :alt="team.name">
						<!-- /member photo -->

						<!-- member social profile -->
						<div class="mask" v-if="team.facebook != null || team.linkedin != null || team.twitter != null || team.email != null">
							<ul class="clearfix">
								<li v-if="team.linkedin != null"><a :href="team.linkedin" target="__blank"><i class="tf-ion-social-linkedin"></i></a></li>
								<li v-if="team.facebook != null"><a :href="team.facebook" target="__blank"><i class="tf-ion-social-facebook"></i></a></li>
								<li v-if="team.twitter != null"><a :href="team.twitter" target="__blank"><i class="tf-ion-social-twitter"></i></a></li>
								<li v-if="team.email != null"><a :href="`mailto:${team.email}`" target="__blank"><i class="tf-ion-social-google-outline"></i></a></li>
							</ul>
						</div>
						<!-- /member social profile -->
					</div>

					<!-- member name & designation -->
					<div class="member-content">
						<h3>{{ team.name }}</h3>
						<span>{{ team.team }}</span>
					</div>
					<!-- /member name & designation -->

				</div>
			</div>
	  </div>
    </div>
    <div class="carousel-item">
		<div class="row">
			<div class="col-md-4 col-sm-6" v-for="team in ourTeam" :key="team.id">
			<div class="team-member text-center">
					<div class="member-photo">
						<!-- member photo -->
						<img class="img-fluid" :src="`/storage/${team.photo}`" :alt="team.name">
						<!-- /member photo -->

						<!-- member social profile -->
						<div class="mask" v-if="team.facebook != null || team.linkedin != null || team.twitter != null || team.email != null">
							<ul class="clearfix">
								<li v-if="team.linkedin != null"><a :href="team.linkedin" target="__blank"><i class="tf-ion-social-linkedin"></i></a></li>
								<li v-if="team.facebook != null"><a :href="team.facebook" target="__blank"><i class="tf-ion-social-facebook"></i></a></li>
								<li v-if="team.twitter != null"><a :href="team.twitter" target="__blank"><i class="tf-ion-social-twitter"></i></a></li>
								<li v-if="team.email != null"><a :href="`mailto:${team.email}`" target="__blank"><i class="tf-ion-social-google-outline"></i></a></li>
							</ul>
						</div>
						<!-- /member social profile -->
					</div>

					<!-- member name & designation -->
					<div class="member-content">
						<h3>{{ team.name }}</h3>
						<span>{{ team.team }}</span>
					</div>
					<!-- /member name & designation -->

				</div>
			</div>
	  </div>
	</div>
  </div>
  <a class="carousel-control-prev" href="#team" role="button" data-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="carousel-control-next" href="#team" role="button" data-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div>
	
		</div> <!-- End row -->
	
</section> 
  </template>

<script>
export default{
  props: {ourTeam: Object}
}
</script>