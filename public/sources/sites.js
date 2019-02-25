sources_sites = {
    data(){
        return{
            sites:[]
        }
    },
    methods:{
        getSiteName: function (id) {
            for (i = 0; i < this.sites.length; i++) {
                if (this.sites[i].id == id) return this.sites[i].complete_name;
            }
        },
        siteName: function (id) {
            site = this.sites.find(s => s.id == id);
            if (site) return site.complete_name;
            else return "";
        },
        list_sites: function () {
            $.ajax({
                url: "{{route('site.list')}}",
                method: "GET",
                dataType: "json",
            }).done(response => {
                this.sites = response;
            });
        },
    }
}
