$(document).ready(function () {

    // cancel the cancelling migration
    $('#cancelMigrationBtn').on('click', function () {
        $('.cancelMigrationModal').addClass('hidden')
    })

    // close the modal migration
    $('#closeMigrationModal').on('click', function () {
        $('.cancelMigrationModal').addClass('hidden')
        $('.migrationModal').addClass('hidden')
    })

    // open cancelling button
    $('.cancelMigration').on('click', function () {
        $('.cancelMigrationModal').removeClass('hidden')
    })
})