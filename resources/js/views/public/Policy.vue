<template>
    <div>

        <div id="loader2" v-if="loader">
            <div class="loader-bar"></div>
        </div>
        <div class="col-lg-12 header-title text-center">
			POLICY - {{formtitle}}
		</div>
        <!-- ../storage/app/public/policy/3/021128.pdf -->
        <div class="col-lg-12 margin-15" style="overflow: auto;">
            <iframe :src="frame_src" width="100%" height="1111px"></iframe>
        </div>
        <div class="clearfix"></div>
        <br><br>
    </div>
</template>
<script>

export default {

    data(){
        return{
            frame_src: '',///'../storage/app/public/policy/3/021128.pdf',
            formtitle: '',
            loader: true,
            classIn: '',
            rows: [],
            selectedSlip: {},
            dataURI: '',
        }
    },
    watch:{
        $route (to, from){
            this.pullPolicy(this.$route.params.policy_id);
        }
    },
    filters:{
        formatMonth(value)
        {
            return moment(value).format('MMM Do');
        },
        formatMonthPrint(value)
        {
            return moment(value).format('MMM Do YYYY');
        },
    },
    methods:{
        pullPolicy(params = ''){
            axios.get('api/policy-per-detail/'+params)
            .then((response) => {

                this.loader = false;
                if(response.data.length>0){
                    this.frame_src = '../storage/app/'+response.data[0].pdf_loc;
                    this.formtitle = response.data[0].catname;
                }

            })
            .catch(err => console.log(err));
        },
    },
    computed:{

    },
    mounted(){
        // this.forapprover = ((this.$route.path).slice(1)).toLowerCase().split('-')[];
        let path = this.$router.currentRoute.path;
        // this.formtitle = (path.slice(path.lastIndexOf("/")+1)).replace(/-/g, ' ').toUpperCase();

        this.$root.$data.baseURL = '../';

        this.pullPolicy(this.$route.params.policy_id);

    }

}
</script>