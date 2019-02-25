sources_employess = {
    data(){
        return{
            employess:[]
        }
    },
    methods:{
        list_employess: function () {
            $.ajax({
                url: "{{route('emp.list')}}",
                method: "GET",
                dataType: "json",
            }).done(response => {
                this.employees = response;
            });
        },
        store_employee: function () {
            if (this.$refs.form.validate()) {
                app.confirm("Criando/Alterando Registro!", "Confirmar ação deste Registro?",
                    "green", () => {
                        $.ajax({
                            url: "{{route('emp.store')}}",
                            method: "POST",
                            dataType: "json",
                            headers: app.headers,
                            data: this.form,
                            success: (response) => {
                                this.list();
                                this.form_view = false;
                                if (this.form.id == "") app.notify(
                                    "Empregado adicionado",
                                    "success");
                                else app.notify("Edição salva", "success");
                                if (this.form.id == "") app.notify(
                                    "Empregado adicionado com sucesso!",
                                    "success");
                                else app.notify("Edição salva", "success");
                            }
                        });
                    })
            }
        },
        getEmployee: function (id) {
            for (j = 0; j < this.employees.length; j++) {
                if (id == this.employees[j].id) return this.employees[j];
            }
            return null;
        },
    }
}
