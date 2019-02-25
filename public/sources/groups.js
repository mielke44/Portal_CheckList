sources_groups = {
    data(){
        return{
            groups:[]
        }
    },
    methods:{
        list_group: function(){
            $.ajax({
                url: "{{route('group.list')}}",
                method: "GET",
                dataType: "json",
            }).done(response => {
                for (r of response){
                    r.id = 'group'+r.id;
                }
                this.resp = this.resp.concat(response);
            })
        },
    }
}
