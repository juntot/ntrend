<template>
    <div>
        <h2>GENERAL SETTINGS</h2>
        <br>
        <h5 class="orange-text">Company Logo</h5>
        <div class="avatar-container text-left">
            <label for="file" id="avatarlbl">
                <img id="preview" :src="'storage/app/public/images/comp_logo.png'" alt="Avatar" style="width: 100%; height: auto; max-width: 300px;">
            </label>
            <input id="file" type="file" @change="newLogo" style="display:none;">
        </div>
    </div>
</template>
<script>
export default {
    data: function () {
		return {
            avatar: '',
		}
    },
    methods:{
        newLogo(){
			if(document.querySelector('input#file[type=file]').value != ''){
	        	let preview = document.querySelector('img#preview'); //selects the query named img
			    let file    = document.querySelector('input#file[type=file]').files[0]; //sames as here

			    let type = file['type'];
			    const validImageTypes = ['image/gif', 'image/jpeg', 'image/png'];
			    if (!validImageTypes.includes(type)) {
				      alert('Invalid Image type');
				      return false;
				}
				if(file.size >= 8000000)
				{
					alert('Filesize exceed 8MB');
				    return false;
				}

				if (file) {

                    //  return file;

                    const formData = new FormData();
                    formData.append( 'image', file );

                    axios.post('api/change_logo', formData)
                    .then((response)=>{
                        let reader  = new FileReader();
                            reader.onloadend = function () {
                                preview.src = reader.result;
                            }
                            reader.readAsDataURL(file);
                        // this.$validator.reset();
                    }).catch((err)=>{
                        // self.clear();
                    });
				}
			}else{

				return null;
			}
        },

    }
}
</script>