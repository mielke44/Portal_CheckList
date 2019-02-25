sources_checklists_template = {
    data(){
        return{
            templates:[]
        }
    },
    methods:{
        list_ChecklistTemplate: function () {
            $.ajax({
                url: "{{route('checklist.list')}}",
                method: "GET",
                dataType: "json",
            }).done(response => {
                this.templates = response;
            });
        },
        getTemplate(id) {
            for (t of this.templates) {
                if (t.id == id) return t;
            }
            return null;
        },
    }
}
