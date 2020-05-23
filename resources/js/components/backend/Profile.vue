<style>
    .widget-user-header{
        background-position: center;
        background-size: cover;
        height: 250vh;
    }
</style>
<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12 mt-3">
                <div class="card card-widget widget-user">
                    <!-- Add the bg color to the header using any of the bg-* classes -->
                    <div class="widget-user-header text-white" style="background-image:url('./images/user-cover.png')">
                        <h3 class="widget-user-username text-right">Elizabeth Pierce</h3>
                        <h5 class="widget-user-desc text-right">Web Designer</h5>
                    </div>
                    <div class="widget-user-image">
                        <img class="img-circle" :src="getProfilePhoto()" alt="User Avatar">
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-sm-4 border-right">
                                <div class="description-block">
                                    <h5 class="description-header">3,200</h5>
                                    <span class="description-text">SALES</span>
                                </div>
                                <!-- /.description-block -->
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-4 border-right">
                                <div class="description-block">
                                    <h5 class="description-header">13,000</h5>
                                    <span class="description-text">FOLLOWERS</span>
                                </div>
                                <!-- /.description-block -->
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-4">
                                <div class="description-block">
                                    <h5 class="description-header">35</h5>
                                    <span class="description-text">PRODUCTS</span>
                                </div>
                                <!-- /.description-block -->
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header p-2">
                        <ul class="nav nav-pills">
                            <li class="nav-item"><a class="nav-link active" href="#activity" data-toggle="tab">Activity</a></li>
                            <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">Settings</a></li>
                        </ul>
                    </div><!-- /.card-header -->
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="active tab-pane" id="activity">

                            </div>
                            <!-- /.tab-pane -->

                            <div class="tab-pane" id="settings">
                                <form class="form-horizontal">
                                    <div class="form-group row">
                                        <label for="inputName" class="col-sm-2 col-form-label">Name</label>
                                        <div class="col-sm-10">
                                            <input type="text" v-model="form.name" name="name" class="form-control" id="inputName" placeholder="Name" :class="{ 'is-invalid': form.errors.has('name') }">
                                            <has-error :form="form" field="name"></has-error>
                                        </div>

                                    </div>
                                    <div class="form-group row">
                                        <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                                        <div class="col-sm-10">
                                            <input type="email" v-model="form.email" name="email" class="form-control" id="inputEmail" placeholder="Email" :class="{ 'is-invalid': form.errors.has('email') }">
                                            <has-error :form="form" field="email"></has-error>
                                        </div>

                                    </div>

                                    <div class="form-group row">
                                        <label for="inputExperience" class="col-sm-2 col-form-label">Bio</label>
                                        <div class="col-sm-10">
                                            <textarea class="form-control" name="bio" id="inputExperience" v-model="form.bio" placeholder="Experience" :class="{ 'is-invalid': form.errors.has('bio') }"></textarea>
                                            <has-error :form="form" field="bio"></has-error>
                                        </div>

                                    </div>

                                    <div class="form-group row">
                                        <label for="inputExperience" class="col-sm-2 col-form-label">Profile Photo</label>
                                        <div class="col-sm-10">
                                            <input type="file" @change="updateProfile" name="photo">

                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="inputPassword" class="col-sm-2 col-form-label">Password </label>
                                        <div class="col-sm-10">
                                            <input type="password" v-model="form.password" name="password" class="form-control" id="inputPassword" placeholder="Password (Leave empty if not changing)" :class="{ 'is-invalid': form.errors.has('password') }">
                                            <has-error :form="form" field="password"></has-error>
                                        </div>

                                    </div>

                                    <div class="form-group row">
                                        <div class="offset-sm-2 col-sm-10">
                                            <button @click.prevent="UpdateInfo" type="submit" class="btn btn-primary">Update</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- /.tab-pane -->
                        </div>
                        <!-- /.tab-content -->
                    </div><!-- /.card-body -->
                </div>
                <!-- /.nav-tabs-custom -->
            </div>
        </div>

    </div>

</template>

<script>
    export default {
        data(){
            return{
                form: new Form({
                    id: "",
                    name: '',
                    email: '',
                    password: '',
                    type: '',
                    bio: '',
                    photo: '',
                })
            }
        },
        mounted() {
            console.log('Component mounted.')
        },
        methods:{
            getProfilePhoto(){
                let photo = (this.form.photo.length > 100)? this.form.photo:"images/profile/"+this.form.photo ;
                return photo
            },
            UpdateInfo(){
                this.$Progress.start();
                this.form.put('api/profile/')
                    .then(()=>{
                        Fire.$emit('AfterCreate');
                        Swal.fire(
                            'Updated!',
                            'Information has been updated.',
                            'success'
                        );
                        this.$Progress.finish();

                    }).catch(() => {
                    this.$Progress.fail();

                });
            },
            updateProfile(e){
                let file = e.target.files[0];
                console.log(file);
                let reader = new FileReader();
                if(file['size'] < 2111775){
                    reader.onloadend = (file) => {
                        //console.log('Result', reader.result)
                        this.form.photo = reader.result
                    };
                    reader.readAsDataURL(file);
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Your file size is larger than 2MB!',
                    })
                }


            }
        },

        created() {
            axios.get('api/profile').then(({data}) => (this.form.fill(data)));
        }
    }
</script>
