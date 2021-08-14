<template>
    <!-- <div class="col-lg-12">
        404 | page not found...
    </div> -->
    <div class="">
        <div class="pagenotfound dflex d-align-center justify-content-center">
            <div class="code">
                404
            </div>
            <div class="message" style="padding: 10px;">
               Page Not Found
            </div>
        </div>
    </div>
    
</template>

<script>
export default {

    beforeDestroy(){
        $('#content').removeClass('transparent');
    },
    mounted(){
        $('#content').addClass('transparent');
    }
}
</script>
