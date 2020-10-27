<?php

use Illuminate\Http\Request;
use \Illuminate\Http\Response;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
//Route::get('/user', 'UserController@getUserInfo')->name('admin.userInfo');

//Route::middleware('auth:api')->get('/user', 'UserController@getUserInfo')->name('admin.userInfo');
//Route::post('/login', 'Auth\LoginController@login')->name('login.login');
//Route::post('/login', 'Auth\LoginController@login')->name('login.login');
//Route::post('/loginWithThree', 'Auth\LoginController@loginWithThree')->name('login.loginWithThree');
//Route::post('/token/refresh', 'Auth\LoginController@refresh')->name('login.refresh');
//Route::post('/logout', 'Auth\LoginController@logout')->name('login.logout');
//Route::post('/test', 'UserController@destroy')->name('soft.test');
//Route::middleware('auth:api')->group(function() {
//    // 用户管理
//
//    Route::post('/admin/modify', 'UserController@modify' )->name('admin.modify');
//    Route::post('/admin/{id}/reset', 'UserController@reset')->name('admin.reset');
//    Route::post('/admin/uploadAvatar', 'UserController@uploadAvatar')->name('admin.uploadAvatar');
//    Route::post('/admin/upload', 'UserController@upload')->name('admin.upload');
//    Route::post('/admin/export', 'UserController@export')->name('admin.export');
//    Route::post('/admin/exportAll', 'UserController@exportAll')->name('admin.exportAll');
//    Route::post('/admin/deleteAll', 'UserController@deleteAll')->name('admin.deleteAll');
//
//    // 角色管理
//    Route::Resource('role', 'RoleController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
//    Route::get('getRoles', 'RoleController@getRoles')->name('role.get');
//
//    // 其他支持API
//    Route::get('/getSession', 'SessionController@getSession')->name('session.get'); // 获取所有学期
//    Route::get('/getDefaultSession', 'SessionController@getDefaultSession')->name('session.getDefault'); //获得当前学期
//    Route::get('/getClassNumByGrade', 'SessionController@getClassNumByGrade')->name('session.getClassNum'); // 根据年级获取最大班级数
//
//
//    // 学期管理
//    Route::Resource('session', 'SessionController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
//    Route::post('/session/upload', 'SessionController@upload')->name('session.upload');
//
//    // 程序功能管理
//    Route::Resource('permissions', 'PermissionController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
//    Route::post('/permissions/addGroup', 'PermissionController@addGroup')->name('permissions.addGroup');
//    Route::post('/permissions/getGroup', 'PermissionController@getGroup')->name('permissions.getGroup');
//    Route::post('/permissions/deleteAll', 'PermissionController@deleteAll')->name('permissions.deleteAll') ;
//    Route::post('/permissions/getPermissionByTree', 'PermissionController@getPermissionByTree')->name('Permission.getPermissionByTree');
//
//    // 手机信息管理
//    Route::post('/sms/send', 'SmsController@send')->name('sms.send');
//    Route::post('/sms/verify', 'SmsController@verify')->name('sms.verify');
//
//    // 学生信息管理
//
//    Route::post('students/deleteAll', 'StudentController@deleteAll')->name('students.deleteAll');
//    Route::post('students/upload', 'StudentController@upload')->name('students.upload');
//    Route::post('students/export', 'StudentController@export')->name('students.export');
//    Route::post('students/exportAll', 'StudentController@exportAll')->name('students.exportAll');
//
//    // 日志管理API
//    Route::get('logs/show', 'LogController@show')->name('logs.show'); // 操作日志
//    Route::get('logs', 'LogController@index')->name('logs.index');  // 登录日志
//});
Route::resource('students', 'StudentController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
Route::Resource('admin', 'UserController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);

Route::Resource('rareGene', 'RareGeneController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
//Route::Resource('rareVariant', 'RareVariantController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
//Route::Resource('rareVariantById', 'RareVariantController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
//Route::Resource('rareVariantByGeneSymbol', 'RareVariantController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
//Route::post('rareVariant/inputValue', 'RareVariantController@inputValue');
//Route::post('commonVariant/inputValue', 'CommonVariantController@inputValue');
//Route::post('cnv/inputValue', 'CNVController@inputValue');
//Route::post('dnaMethylation/inputValue', 'DNAMethylationController@inputValue');
//Route::post('geneExpression/inputValue', 'GeneExpressionController@inputValue');
//Route::post('geneStatistics/inputValue', 'GeneStatisticsController@inputValue');

Route::middleware('api')->group(function() {

    Route::post('validatedDatabase/getInfoValidatedDatabase', 'ValidatedDatabaseController@getInfoValidatedDatabase')->name('getInfoValidatedDatabase');
    Route::post('dominantDatabase/getInfoDominantDatabase', 'DominantDatabaseController@getInfoDominantDatabase')->name('getInfoDominantDatabase');

    Route::post('dominantDatabase/getKeyListDominantDatabase', 'DominantDatabaseController@getKeyListDominantDatabase')->name('getKeyListDominantDatabase');

    Route::post('ascrispr/ascrispr', 'AscrisprController@ascrispr')->name('ascrispr.ascrispr');
    Route::post('ascrispr/showInfoSequence', 'AscrisprController@showInfoSequence')->name('ascrispr.showInfoSequence');
    Route::post('ascrispr/getSequences', 'AscrisprController@getSequences');
    Route::post('ascrispr/plotSequences', 'AscrisprController@plotSequences');

    Route::post('ascrispr/showInfoSequenceByTime', 'AscrisprController@showInfoSequenceByTime')->name('ascrispr.showInfoSequenceByTime');


    Route::post('enzymeInformation/getEnzymesInfo', 'EnzymeInformationController@getEnzymesInfo')->name('enzymeInformation.getEnzymesInfo');
    Route::post('offtargets/getOfftargetsInfo', 'OfftargetsController@getOfftargetsInfo')->name('offtargets.getOfftargetsInfo');


    Route::post('rareGene/geneSymbol', 'RareGeneController@geneSymbol');
    Route::post('rareVariant/geneSymbol', 'RareVariantController@geneSymbol');
    Route::post('commonVariant/geneSymbol', 'CommonVariantController@geneSymbol');
    Route::post('cnv/geneSymbol', 'CNVController@geneSymbol');
    Route::post('dnaMethylation/geneSymbol', 'DNAMethylationController@geneSymbol');
    Route::post('geneExpression/geneSymbol', 'GeneExpressionController@geneSymbol');
    Route::post('geneStatistics/geneSymbol', 'GeneStatisticsController@geneSymbol');

    Route::post('geneStatistics/letter', 'GeneStatisticsController@letter');

    Route::post('regionRefgeneHG19/region', 'RegionRefgeneHG19Controller@region');
    Route::post('regionCytobandHG19/cytoband', 'RegionCytobandHG19Controller@cytoband');


    Route::Resource('commonVariant', 'CommonVariantController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
    Route::Resource('cnv', 'CNVController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
    Route::Resource('dnaMethylation', 'DNAMethylationController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
    Route::Resource('geneExpression', 'GeneExpressionController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);




    Route::post('analysis/analysis', 'AnalysisController@analysis')->name('analysis.analysis');
    Route::post('analysis/uploadFiles', 'AnalysisController@uploadFiles')->name('analysis.uploadFiles');

    Route::post('geneInfoHS/geneSymbol', 'GeneInfoHSController@geneSymbol');
    Route::post('geneScore/geneSymbol', 'GeneScoreController@geneSymbol');
    Route::post('gene2goHuman/geneID', 'Gene2goHumanController@geneID');
    Route::post('PPI/uniprotEntry', 'PPIController@uniprotEntry');

    Route::post('biosystemsHuman/geneID', 'BiosystemsHumanController@geneID');
    Route::post('clinVar/geneInfo', 'ClinVarController@geneInfo');

    Route::post('denovoDB/geneSymbol', 'DenovoDBController@geneSymbol');
    Route::post('mgi/geneID', 'MGIController@geneID');
    Route::post('hpo/geneID', 'HPOController@geneID');
    Route::post('homoloGene/geneID', 'HomoloGeneController@geneID');

    Route::post('variantDistribution/geneSymbol', 'VariantDistributionController@geneSymbol');

    Route::post('DGIdb/geneSymbol', 'DGIdbController@geneSymbol');

    Route::post('uniprot/uniprotEntry', 'UniprotController@uniprotEntry');
    Route::post('interproHumanProteinDomain/uniprotEntry', 'InterproHumanProteinDomainController@uniprotEntry');
    Route::post('omim/geneID', 'omimController@geneID');

    Route::post('subcellularLocation/ensemblID', 'SubcellularLocationController@ensemblID');

    Route::post('BSAvgExpression/geneSymbol', 'BSAvgExpressionController@geneSymbol');

    Route::post('GTEx1/ensemblID', 'GTEx1Controller@ensemblID');
    Route::post('GTEx2/ensemblID', 'GTEx2Controller@ensemblID');
});


