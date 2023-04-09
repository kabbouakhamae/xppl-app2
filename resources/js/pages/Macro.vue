<template>
    <div>
        <!-- <div class="row">
            <div class="col-xl-8 col-lg-8 col-12"> -->

                <div class="card card-body">
                    <h4 class="card-title text-muted mb-0 my-auto">Excel Macro</h4>
                    <div class="table-responsive mt-4">
                    <table class="table main-table-reference text-nowrap mb-1">
                        <thead>
                            <tr>
                                <th style="letter-spacing: 0px; padding: 5px 10px">Macro Name</th>
                                <th style="letter-spacing: 0px; padding: 5px 10px">Macro Description</th>
                                <th style="letter-spacing: 0px; padding: 5px 10px">Release Date</th>
                                <th style="letter-spacing: 0px; padding: 5px 10px">Release Note</th>
                                <th style="letter-spacing: 0px; padding: 5px 10px; width: 70%">Actions</th>
                            </tr>
                        </thead>
                        <tbody> 
                            <tr class="tr-hover" v-for="lst in files" :key="lst.id">
                                <td style="padding: 3px 10px">{{lst.macro_name}}</td>
                                <td style="padding: 3px 10px" class="laofont">{{lst.macro_descr}}</td>
                                <td style="padding: 3px 10px">{{lst.release_date}}</td>
                                <td style="padding: 3px 10px" class="laofont">{{lst.release_note}}</td>

                                <td style="padding: 0px 4px; vertical-align: middle">
                                    <div class="d-flex justify-content-start">
                                        <button class="btn btn-icon btn-sm btn-i wd-25 ht-25" title="Download file" @click="fileDownload(lst.macro_name)">
                                            <i class="bx bxs-download text-primary" style="font-size: 16px"></i>
                                        </button> 
                                        <button v-if="permiss.mac_edit == 1" class="btn btn-icon btn-sm btn-i wd-25 ht-25" title="Delete file" @click="fileDel(lst.macro_name)">
                                            <i class="bx bx-trash text-danger" style="font-size: 16px"></i>
                                        </button> 
                                    </div>
                                </td>
                            </tr>                                                                                            
                            <div v-if="permiss.mac_edit == 1" class="mt-1" style="margin-start: 11px">
                                <a class="add-hover p-0" href="#" @click="newFile()" title="Add new file"><span class="tx-13">Add...</span></a>    
                                <input class="d-none" ref="fileInput" type="file" accept=".xlsm" @change="addFile()">
                            </div> 
                        </tbody>
                    </table>
                    </div>

            </div>
            <!-- </div>
        </div> -->
    </div>
</template>

<script>
export default {
    name: 'XpplAppMacro',

    data() {
        return {

            files: [],
            permiss: []
        };
    },

    mounted() {
        
    },

    methods: {
        newFile(){
            this.$refs.fileInput.click();
        },

        addFilez(event){
            // this.empForm.photo = event.target.files[0];

            // let reader = new FileReader();
            // reader.readAsDataURL(this.empForm.photo);
            // reader.addEventListener("load", function(){
            //     this.photoPrev = reader.result;
            // }.bind(this), false);

            let fd = new FormData();
                fd.append('file', this.$refs.fileInput.files.file);
            
            this.$axios.post('/api/permission/addFile', fd, {headers:{"Content-Type": "multipart/form-date"}})
            .then(response => {   

                    // this.getFiles(this.empForm.userid);

                    console.log(this.$refs.fileInput.files.file);

            }).catch (err => {
                console.log(err);
            });
		},

        addFile(){
            let fd = new FormData();
            for (let i = 0; i < this.$refs.fileInput.files.length; i++){
                let file = this.$refs.fileInput.files[i];
                    fd.append('file[' + i + ']', file);
            }
           
                this.$axios.post('/api/permission/addFile', fd, {headers:{"Content-Type": "multipart/form-date"}})
                .then(response => {   

                        this.getFiles();

                }).catch (err => {
                    console.log(err);
                });

           
        },

        getFiles(){
            this.$axios.get('/api/permission/files')
            .then(res => this.files = res.data)
        },

        fileDownload(file){
            const url = `/api/permission/download/${file}`;
            window.location.href = url;
        },

        async loginInfo(){
			// const profile = await axios.get('api/profile')
			// this.headerForm.created_dept = profile.data.department;

            const perRes = await axios.get('/api/permiss')
            this.permiss = perRes.data;

            // this.headerForm.refuel_date = moment(new Date()).format('YYYY-MM-DD');
            // this.refuelDateChanged();
		},
    },

    created(){
        this.loginInfo();
        this.getFiles();
    },

    beforeRouteEnter(to, from, next){
        if(!window.Laravel.isLoggedin_laravel){
            window.location.href = "/signin";
        }
        next();
	},
};
</script>

<style lang="scss" scoped>

</style>