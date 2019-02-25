sources_profiles = {
    data(){
        return{
            profiles:[]
        }
    },
    methods:{
        list_profiles: function () {
            $.ajax({
                url: routes.profile_list,
                method: "GET",
                dataType: "json",
            }).done(response => {
                this.profiles = response;
            });
        },
        store_profile: function (profile,callback) {
            $.ajax({
                url: routes.profile_store,
                method: "POST",
                dataType: "json",
                headers: this.headers,
                data: profile,
                success: (response) => {
                    callback(response);
                }
            });

        },
        delete_profile: function (id,callback) {
            $.ajax({
                url: routes.profile_destroy,
                method: "DELETE",
                dataType: "json",
                headers: app.headers,
                data: {
                    id: id
                },
                success: (response) => {
                    callback(response);
                }
            });
        },
    }
}
