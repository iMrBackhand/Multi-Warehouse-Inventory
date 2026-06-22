$(document).on("click", ".archive-form", function (e) {
    e.preventDefault();

    let form = $(this).closest("form");

    Swal.fire({
        title: "Are you sure?",
        text: "Move this to archive?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, archive it!",
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
});

$(document).on("click", ".restore-form", function (e) {
    e.preventDefault();

    let form = $(this).closest("form");

    Swal.fire({
        title: "Restore Brand?",
        text: "This brand will be moved back to active.",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#28a745",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, restore it!",
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
});
