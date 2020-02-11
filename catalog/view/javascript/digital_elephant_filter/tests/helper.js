var helper = DigitalElephantFilterHelper.instance();

describe("helper.clearEmpty", function() {

    it("helper", function() {
        helper.storageRemoveEmptyParams = null;
        assert.equal(helper.removeEmptyParams('&option=&attribute=12'), '&attribute=12');

        helper.storageRemoveEmptyParams = null;
        assert.equal(helper.removeEmptyParams('option=&attribute=12'), '&attribute=12');

        helper.storageRemoveEmptyParams = null;
        assert.equal(helper.removeEmptyParams('option[4][]=&attribute=12'), '&attribute=12');

        helper.storageRemoveEmptyParams = null;
        assert.equal(helper.removeEmptyParams('&attribute=12&option[4][]='), '&attribute=12');

        helper.storageRemoveEmptyParams = null;
        assert.equal(helper.removeEmptyParams('&attribute=&option[4][]=red'), '&option[4][]=red');

        helper.storageRemoveEmptyParams = null;
        assert.equal(helper.removeEmptyParams('option[4][]=red&attribute=&catalog=32'), 'option[4][]=red&catalog=32');

        helper.storageRemoveEmptyParams = null;
        assert.equal(helper.removeEmptyParams({test: true}), '');

        helper.storageRemoveEmptyParams = null;
        assert.equal(helper.removeEmptyParams('&price%5Bmin%5D=240&price%5Bmax%5D=1395&category%5Bcategories%5D%5B%5D=214&attribute%5B12%5D%5B%5D=&attribute%5B16%5D%5B%5D=&attribute%5B13%5D%5B%5D=&attribute%5B34%5D%5B%5D=&attribute%5B21%5D%5B%5D=&ajax_digital_elephant_filter=1'), '&price%5Bmin%5D=240&price%5Bmax%5D=1395&category%5Bcategories%5D%5B%5D=214&ajax_digital_elephant_filter=1');

        assert.equal(helper.getGetParam('&option[4][]=red&attribute=12&catalog=32', 'attribute'), '&attribute=12');
        assert.equal(helper.getGetParam('&option[4][]=red&attribute=&catalog=32', 'attribute'), '&attribute=');
        assert.equal(helper.getGetParam('&option[4][]=red&attribute=&catalog=32', 'no_exists'), '');
        assert.equal(helper.getGetParam('&attribute=test&option[4][]=red&catalog=32', 'attribute'), '&attribute=test');
        assert.equal(helper.getGetParam('&option[4][]=red&catalog=32&attribute=test', 'attribute'), '&attribute=test');
        assert.equal(helper.getGetParam('&option[4][]=red&option[5][]=blue&catalog=32&attribute=test&attribute=test', 'option'), '&option[4][]=red&option[5][]=blue');

        //assert.equal(helper.getGetParamValue('&option[4][]=red&catalog=32&attribute=test', 'attribute', 'string'), 'test');
        //assert.equal(helper.getGetParamValue('&attribute=test&option[4][]=red&catalog=32', 'attribute', 'string'), 'test');
        //assert.equal(helper.getGetParamValue('&option[4][]=red&attribute=test&catalog=32', 'attribute', 'string'), 'test');
        //assert.equal(helper.getGetParamValue('&option[4][]=red&attribute=&catalog=32', 'attribute', 'string'), '');
    });
});