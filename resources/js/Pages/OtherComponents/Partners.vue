<template>
 <section  class="counter-wrapper section-sm">
			<div class="container">
				<div class="row">
					<div class="col-md-12 text-center">
						<div class="title">
							<h2>Our Partners</h2>
							<p>We understanding the importance of networking, partnership and affiliations, We are currently partnering with</p>
						</div>
					</div>
					<!-- first count item -->
					<div class="col-md-3 col-sm-6 col-xs-6 text-center" v-for="partner in partners" :key="partner.id">
						<div class="counters-item">
                            <a :href="partner.link">
                                <img :src="`/storage/${partner.logo}`" :alt="partner.partner" class="img img-responsive w-50 mb-2" />
                            </a>
							<div>
							    <span class="counter d-none" data-count="150">0</span>
							</div>
							<h3>{{ partner.partner }}</h3>
						</div>
					</div>
					<!-- end first count item -->
				</div> 		<!-- end row -->
			</div>   	<!-- end container -->
		</section> 
      </template>
    
    <script>
    export default{
      props: {partners: Object}
    }
    </script>