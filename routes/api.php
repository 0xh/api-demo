<?php
// use App\Events\AcceptFriendEvent;
Route::group(['prefix' => 'v1', 'namespace' => 'Api\V1'] , function()
{
    Route::group(['prefix' => 'auths'], function()
    {
        Route::post('/login', 'AuthsController@login');
    });

    // Users
    Route::group(['prefix' => 'users'], function()
    {
        Route::post('/register', 'UsersController@register');
    });
});


Route::group(['prefix' => 'v1', 'namespace' => 'Api\V1', 'middleware' => ['api.auth']] , function()
{

    // Admin
    Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => 'api.admin'], function (){
        Route::resource('users', 'UserAPIController');
        //Dashboard
        Route::get('statistical','DashboardAdminApiController@statistical');
        Route::post('getDataPurchases','DashboardAdminApiController@getDataPurchases');
        Route::post('getDataVisitors','DashboardAdminApiController@getDataVisitors');
        Route::post('getDataSubcriptions','DashboardAdminApiController@getDataSubcriptions');
    });


    // Company
    Route::group(['prefix' => 'company', 'namespace' => 'Company', 'middleware' => 'api.company'], function() {
        //
        Route::post('addEmployee','CompanyAPIController@addEmployee');

        Route::post('removeEmployee','CompanyAPIController@removeEmployee');

    });

    // Walker
    Route::group(['prefix' => 'walker', 'namespace' => 'Walker', 'middleware' => 'api.walker'], function() {
        Route::get('getPetOfTheCompanyClient/{idUser}','PetAPIController@getPetOfTheCompanyClient');
        Route::get('test',function(){
            $company = \App\Models\Company::all()->first();
            dd($company->toArray());
        });
    });

    // Client
    Route::group(['prefix' => 'client', 'namespace' => 'Client', 'middleware' => 'api.client'], function() {
        //
    });

    Route::group(['prefix' => 'site', 'namespace' => 'Site'], function (){
        // Shop product
        Route::get('getProducts', 'ProductAPIController@getProducts');
        Route::get('showDetailProduct/{id}','ProductAPIController@showDetailProduct');
        // Activities Pet

        // Pets
        Route::get('getListPetsOfUser/{userId}', 'PetAPIController@getListPetsOfUser');
        Route::get('getListPetsOfUserNofence/{userId}', 'PetAPIController@getListPetsOfUserNofence');
        Route::get('getPetLocationPaginate/{userId}/{per_page}','PetAPIController@getPetLocationPaginate');
        // Fences
        Route::get('getFence/{id}', 'FenceAPIController@getFence');
        Route::get('getFenceOfUser/{id}','FenceAPIController@getFenceOfUser');
        Route::post('createFence','FenceAPIController@createFence');
        Route::put('updateFence/{id}','FenceAPIController@updateFence');
        Route::delete('deleteFence/{id}', 'FenceAPIController@deleteFence');
    });

    Route::group(['middleware' => 'api.admin'], function (){

        Route::resource('plans', 'PlanAPIController');

        Route::resource('subscriptions', 'SubscriptionAPIController');

        Route::resource('animals', 'AnimalAPIController');

        Route::resource('breeds', 'BreedAPIController');



        Route::resource('devices', 'DeviceAPIController');

        Route::resource('users', 'UserAPIController');


        Route::resource('categories', 'CategoryAPIController');

        Route::resource('products', 'ProductAPIController');

        // Invoices
        Route::get('getInvoices', 'SubscriptionAPIController@getInvoices');
        Route::get('retrieveInvoice/{invoiceId}', 'SubscriptionAPIController@retrieveInvoice');

        // Payments
        Route::get('getPayments', 'SubscriptionAPIController@getPayments');
        Route::get('retrievePayment/{paymentId}', 'SubscriptionAPIController@retrievePayment');

        Route::get('retrieveBalanceTransaction/{balanceTransactionId}', 'SubscriptionAPIController@retrieveBalanceTransaction');

        Route::get('getEvents', 'SubscriptionAPIController@getEvents');

        // Categories
        Route::get('getCategories', 'CategoryAPIController@getCategories');


        // Products

        Route::get('getProducts', 'ProductAPIController@getProducts');

        Route::post('createProduct','ProductAPIController@createProduct');

        Route::post('updateProduct/{id}','ProductAPIController@updateProduct');

        Route::post('delImages','ImageAPIController@delImages');


        // Users
        Route::get('getUsers', 'UserAPIController@getUsers');
        Route::post('createUserProfile', 'UserAPIController@createUserProfile');
        Route::put('updateUserProfile/{id}', 'UserAPIController@updateUserProfile');

        //Profile
        Route::get('getProfiles', 'ProfileAPIController@getProfiles');

        // Companies
        Route::get('getAllCompanies','CompanyAPIController@getAllCompanies');
        // Pet
        Route::get('getAllPetsWithAvatar','PetAPIController@getAllPets');

        // Vetrinaty Clinics

        // Route::resource('clinic_services', 'Clinic_serviceAPIController');

        Route::resource('clinics', 'ClinicAPIController');

        Route::post('createClinic','ClinicAPIController@createClinic');

        Route::put('updateClinic/{id}','ClinicAPIController@updateClinic');

        Route::delete('deleteClinic/{id}','ClinicAPIController@deleteClinic');

        Route::get('getPaymentTransaction', 'CartAPIController@getPaymentTransaction');

        Route::get('getCountSubscription', 'SubscriptionAPIController@getCountSubscription');

    });

    Route::resource('fences', 'FenceAPIController');

    Route::resource('pet_fences', 'Pet_fenceAPIController');

    Route::resource('fence_datas', 'FenceDataAPIController');

    Route::resource('profiles', 'ProfileAPIController');

    Route::resource('pets', 'PetAPIController');

    Route::resource('companies', 'CompanyAPIController');

    Route::resource('rolls', 'RollAPIController');

    Route::resource('naps', 'NapAPIController');

    Route::resource('jumps', 'JumpAPIController');

    Route::resource('locations', 'LocationAPIController');

    Route::resource('images', 'ImageAPIController');

    Route::resource('ratings', 'RatingAPIController');

    Route::resource('smiles', 'SmileAPIController');

    Route::resource('carts', 'CartAPIController');

    Route::resource('notifications', 'NotificationAPIController');

    Route::resource('clinic_ratings', 'Clinic_ratingAPIController');

    Route::resource('clinic_services', 'Clinic_serviceAPIController');

    Route::resource('friendships', 'FriendshipAPIController');

    // Users subscribed a Plan
    Route::post('subscribePlan', 'SubscriptionAPIController@subscribePlan');
    Route::get('getSubscribePlan', 'SubscriptionAPIController@getSubscribePlan');

    // Invoices
    Route::get('getInvoices', 'SubscriptionAPIController@getInvoices');
    Route::get('retrieveInvoice/{invoiceId}', 'SubscriptionAPIController@retrieveInvoice');

    // Payments
    Route::get('getPayments', 'SubscriptionAPIController@getPayments');
    Route::get('retrievePayment/{paymentId}', 'SubscriptionAPIController@retrievePayment');
    Route::get('retrieveBalanceTransaction/{balanceTransactionId}', 'SubscriptionAPIController@retrieveBalanceTransaction');
    Route::get('getEvents', 'SubscriptionAPIController@getEvents');

    // Devices
    Route::get('getDevices/{UserID}','DeviceAPIController@getDevicesOfUser');

    Route::get('assignDevice', 'DeviceAPIController@assignDevice');
    Route::get('unassignDevice/{id}', 'DeviceAPIController@unassignDevice');
    Route::post('shareDevice', 'DeviceAPIController@shareDevice');
    Route::get('getDevicesWithShareDevices/{id}','DeviceAPIController@getDevicesWithShareDevices');
    Route::post('unShareDevice','DeviceAPIController@unShareDevice');

    // Pet
    Route::get('getPets/{userID}','PetAPIController@getPets');
    Route::get('getPetLocation/{id}','PetAPIController@getPetLocation');
    Route::get('getPetLocationPaginate/{userId}/{per_page}','PetAPIController@getPetLocationPaginate');
    Route::get('getPetOfUserWithAvatar/{id}','PetAPIController@getPetOfUserWithAvatar');
    Route::get('getPetsNoFence/{userID}','PetAPIController@getPetsNoFence');

    Route::get('getAllAnimals', 'AnimalAPIController@getAllAnimals');
    Route::get('getAllBreeds', 'BreedAPIController@getAllBreeds');

    Route::get('getJumpsOfPet/{petId}', 'PetAPIController@getJumpsOfPet');
    Route::get('getNapsOfPet/{petId}', 'PetAPIController@getNapsOfPet');
    Route::get('getRollsOfPet/{petId}', 'PetAPIController@getRollsOfPet');
    Route::get('getSmilesOfPet/{petId}', 'PetAPIController@getSmilesOfPet');

    Route::get('getJumpsInTimeOfPet/{petId}','PetAPIController@getJumpsInTimeOfPet');
    Route::get('getNapsInTimeOfPet/{petId}','PetAPIController@getNapsInTimeOfPet');
    Route::get('getRollsInTimeOfPet/{petId}','PetAPIController@getRollsInTimeOfPet');
    Route::get('getSmilesInTimeOfPet/{petId}','PetAPIController@getSmilesInTimeOfPet');
    // Images
    // Route::post('uploadAvatar','ImageAPIController@uploadAvatar');
    // Route::get('getAvatar','ImageAPIController@getAvatar');
    // Get Company user belongto
    Route::get('getCompanyRatings/{id}','UserAPIController@getCompanyRatings');
    // Company
    Route::get('getCompaniesOfUser/{id}','CompanyAPIController@getCompaniesOfUser');

    Route::get('getCompaniesOfUserHasMany/{id}','CompanyAPIController@getCompaniesOfUserHasMany');
    ///-----
    Route::get('demoproduct/{id}','DeviceAPIController@demoproduct');

    Route::post('uploadFiles','ImageAPIController@uploadFiles');
    Route::get('getUserAvatar/{id}','UserAPIController@getUserAvatar');

    // Profile - credit
    Route::post('createCreditCard/{id}','ProfileAPIController@createCreditCard');
    Route::get('getProfile/{id}','ProfileAPIController@getProfile');
    Route::get('getInforCreditCard/{id}','ProfileAPIController@getInforCreditCard');

    // Cart
    Route::get('getCart/{userID}', 'CartAPIController@getCart');
    Route::post('checkout/{userID}', 'CartAPIController@checkout');
    Route::put('carts/{id}/update-qty', 'CartAPIController@updateQty');

    /// Fence
    Route::post('createFence','FenceAPIController@createFence');

    Route::get('getFenceOfPet/{id}','FenceAPIController@getFenceOfPet');

    Route::get('getAllFence','FenceAPIController@getAllFence');

    Route::get('getFenceOfUser/{id}','FenceAPIController@getFenceOfUser');

    Route::delete('deleteFence/{id}','FenceAPIController@deleteFence');

    Route::post('addFenceForPet','FenceAPIController@addFenceForPet');

    Route::post('updateFence/{id}','FenceAPIController@updateFence');

    Route::get('getPetFence/{id}','FenceAPIController@getPetFence');

    /// Notificatoin

    Route::get('getNotifications/{id}','NotificationAPIController@getNotifications');

    Route::get('checkSeenNotification/{userId}','NotificationAPIController@checkSeenNotification');

    Route::get('checkReadNotification/{NotiId}','NotificationAPIController@checkReadNotification');

    ///Netrinary Clinic

    Route::get('getClinics','ClinicAPIController@getClinics');

    Route::get('showClinic/{id}','ClinicAPIController@showClinic');

    //Friendship

    Route::post('addFriend','FriendshipAPIController@addFriend');

    Route::get('getListFriend/{id}','FriendshipAPIController@getListFriend');

    Route::get('getListEmployees/{id}','FriendshipAPIController@getListEmployees');
    
    Route::get('getListMembers/{id}','FriendshipAPIController@getListMembers');
    
    Route::get('getOwner/{id}','FriendshipAPIController@getOwner');

    Route::post('unFriend','FriendshipAPIController@unFriend');

    Route::post('searchEmailFriend','FriendshipAPIController@searchEmailFriend');

    //User
    Route::get('getUser/{id}','UserAPIController@show');

});
//Route public
Route::group(['prefix' => 'v1', 'namespace' => 'Api\V1'] , function()
{
    Route::post('getAvatarPublic','ImageAPIController@getAvatarPublic');

    Route::get('getAllCompaniesPublic','CompanyAPIController@index');

    Route::get('generateKey/{imei}', 'DeviceAPIController@generateKey');
    Route::post('saveDeviceInfo/{imei}', 'DeviceAPIController@saveDeviceInfo');
    Route::get('getAllCountries','CountryController@getAll');
    Route::get('getAllCitiesOfCountry/{code}','CityController@getCities');

    Route::resource('messages', 'MessageAPIController');
    Route::post('createConversation','MessageAPIController@createConversation');
    Route::get('getMessages/{id}','MessageAPIController@getMessages');
    Route::get('getMoreMessages','MessageAPIController@getMoreMessages');
    Route::post('sendMessage','MessageAPIController@sendMessage');
    Route::get('getConversations/{id}','MessageAPIController@getConversations');
    Route::post('checktReadMessage','MessageAPIController@checktReadMessage');
    Route::put('editNameConversation','MessageAPIController@editNameConversation');
    Route::delete('deleteConversation/{id}','MessageAPIController@deleteConversation');
    Route::post('checkConversation','MessageAPIController@checkConversation');
    Route::post('addMemberToConversation/{id}','MessageAPIController@addMemberToConversation');
    Route::post('removeMemberToConversation','MessageAPIController@removeMemberToConversation');

    Route::post('updateProduct','ProductAPIController@updateProduct');

    Route::get('getUrlImage/{id}','ImageAPIController@getUrlImage');

    Route::post('confirmAddFriend','FriendshipAPIController@confirmAddFriend');

    Route::post('confirmInvitationCompany','Company\CompanyAPIController@confirmInvitationCompany');
    
    Route::post('comfirmInvitationCompanyByEmail','Company\CompanyAPIController@comfirmInvitationCompanyByEmail');

    Route::get('mergedJumpsOfPet/{id}','PetAPIController@mergedJumpsOfPet');
    Route::get('mergedNapsOfPet/{id}','PetAPIController@mergedNapsOfPet');
    Route::get('mergedSmilesOfPet/{id}','PetAPIController@mergedSmilesOfPet');
    Route::get('mergedRollsOfPet/{id}','PetAPIController@mergedRollsOfPet');
});
