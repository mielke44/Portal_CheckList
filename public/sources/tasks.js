sources_tasks = {
    data(){
        return{
            tasks:[]
        }
    },
    methods:{
        list_tasks: function () {
            $.ajax({
                url: "{{route('task.list')}}",
                method: "GET",
                dataType: "json",
            }).done(response => {
                this.tasks = response;
            });
        },
        getTask: function (id) {
            for (j = 0; j < this.tasks.length; j++) {
                if (id == this.tasks[j].id) return this.tasks[j]
            }
            return null;
        },
    }
}
