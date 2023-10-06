let table;
function loadTable() {
    table = $("#products").DataTable({
        lengthMenu: [10, 25, 50],
        pageLength: 10,
        responsive: true,
        order: [[0, "desc"]],
        language: {
            url:
                "https://cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json",
            searchPlaceholder: "Buscar"
        }
    });
}

function showInfo() {
    var type = localStorage.getItem("type_product");

    switch (type) {
        case "create":
            $("#alert-product").html(
                "Se ha creado el producto correctamente !" +
                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                    '<span aria-hidden="true">&times;</span>' +
                    "</button>"
            );
            $("#alert-product").show();
            removeItem();
            break;
        case "update":
            $("#alert-product").html(
                "Se ha actualizado el producto correctamente !" +
                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                    '<span aria-hidden="true">&times;</span>' +
                    "</button>"
            );
            $("#alert-product").show();
            removeItem();
            break;
        default:
            break;
    }
}

showInfo();
loadTable();

function showModal(id, name) {
    clearForm();

    $("#id").val(id);
    $.get("products/" + id, function(response) {
        $("#name").val(response.name);
        $("#url").val(response.url);
    });

    $("#exampleModalLabel").html("Editar <b>" + name + "</b>");
    $("#modalProduct").modal("show");
}

function clearForm() {
    $("#exampleModalLabel").text("Nuevo Producto");
    $("#id").val("");

    $("#name").val("");
    $("#name").removeClass("is-invalid");
    $("#info-name").hide();

    $("#url").val("");
    $("#category_id").removeClass("is-invalid");
    $("#info-category_id").hide();
}

$("#btn-save").on("click", function() {
    var id = $("#id").val();
    var name = $("#name").val();
    var type = id == "" ? "store" : "update";
    var url = $("#url").val();

    if (name != "") {
        $("#name").removeClass("is-invalid");
        $("#info-name").hide();

        if (url != "") {
            $("#category_id").removeClass("is-invalid");
            $("#info-category_id").hide();

            if (type == "store") {
                $.get("products/get-name", { name: name }, function(response) {
                    if (!response) {
                        continueForm();
                    } else {
                        $("#name").addClass("is-invalid");
                        $("#info-name")
                            .text("El producto ya existe")
                            .show();
                    }
                });
            } else {
                continueForm();
            }
        } else {
            $("#category_id").addClass("is-invalid");
            $("#info-category_id")
                .text("El campo categoría es requerido")
                .show();
        }
    } else {
        $("#name").addClass("is-invalid");
        $("#info-name")
            .text("El campo nombre es requerido")
            .show();
    }
});

function continueForm() {
    var id = $("#id").val();
    var name = $("#name").val();
    var url = $("#url").val();
    var token = $("meta[name=csrf-token]").attr("content");
    var type = "create";

    $.post(
        "products",
        { _token: token, id: id, name: name, url: url },
        function(response) {
            if (response.id == id) {
                type = "update";
            }
            $("#modalCategory").modal("hide");
            loadItem(type);
        }
    );
}

function loadItem(type) {
    localStorage.setItem("type_product", type);
    location.reload();
}
function removeItem() {
    localStorage.removeItem("type_product");
}
