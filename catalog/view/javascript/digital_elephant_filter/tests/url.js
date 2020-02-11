var url = DigitalElephantFilterUrl.instance();

describe("url.stringToArray", function() {
    it("return array by url $_GET params", function () {

        var expectedData = [];
        expectedData['attribute'] = '12';
        expectedData['option'] = 'red';
        var data = 'attribute=12&option=red';
        expect(url.stringToArray(data)).to.be.an('array');
        if (assertArray.assertArray(url.stringToArray(data), expectedData)) {
            assert.isOk(true);
        } else {
            expect(url.stringToArray(data)).to.equal(expectedData)
        }


        data = '&attribute=12&option=red';
        expect(url.stringToArray(data)).to.be.an('array');
        if (assertArray.assertArray(url.stringToArray(data), expectedData)) {
            assert.isOk(true);
        } else {
            expect(url.stringToArray(data)).to.equal(expectedData)
        }

        data = '?attribute=12&option=red';
        expect(url.stringToArray(data)).to.be.an('array');
        if (assertArray.assertArray(url.stringToArray(data), expectedData)) {
            assert.isOk(true);
        } else {
            expect(url.stringToArray(data)).to.equal(expectedData)
        }

        //case multiarray
        data = '&option[5][]=39&option[13][]=50&option[13][]=49&attribute=red';
        expectedData = [];
        expectedData['option'] = [];
        expectedData['option']['5'] = ['39'];
        expectedData['option']['13'] = ['50', '49'];
        expectedData['attribute'] = 'red';

        expect(url.stringToArray(data)).to.be.an('array');
        if (assertArray.assertArray(url.stringToArray(data), expectedData)) {
            assert.isOk(true);
        } else {
            expect(url.stringToArray(data), 'case multiarray').to.equal(expectedData)
        }

        //case empty
        data = '';
        expectedData = [];
        expect(url.stringToArray(data)).to.be.an('array');
        if (assertArray.assertArray(url.stringToArray(data), expectedData)) {
            assert.isOk(true);
        } else {
            expect(url.stringToArray(data), 'case empty').to.equal(expectedData)
        }
    });
});

describe("url.removeEmptyParams", function() {
    it("return string url without empty $_GET params", function () {
        assert.equal(url.removeEmptyParams('&option=&attribute=12'), '&attribute=12');
        assert.equal(url.removeEmptyParams('option=&attribute=12'), '&attribute=12');
        assert.equal(url.removeEmptyParams('option[4][]=&attribute=12'), '&attribute=12');
        assert.equal(url.removeEmptyParams('&attribute=12&option[4][]='), '&attribute=12');
        assert.equal(url.removeEmptyParams('&attribute=&option[4][]=red'), '&option[4][]=red');
        assert.equal(url.removeEmptyParams('option[4][]=red&attribute=&catalog=32'), 'option[4][]=red&catalog=32');
        assert.equal(url.removeEmptyParams({test: true}), '');
        assert.equal(url.removeEmptyParams('&price%5Bmin%5D=240&price%5Bmax%5D=1395&category%5Bcategories%5D%5B%5D=214&attribute%5B12%5D%5B%5D=&attribute%5B16%5D%5B%5D=&attribute%5B13%5D%5B%5D=&attribute%5B34%5D%5B%5D=&attribute%5B21%5D%5B%5D=&ajax_digital_elephant_filter=1'), '&price%5Bmin%5D=240&price%5Bmax%5D=1395&category%5Bcategories%5D%5B%5D=214&ajax_digital_elephant_filter=1');
    });
});

describe("url.getGetParam", function() {
    it("return string $_GET params", function () {
        assert.equal(url.getGetParam('&option[4][]=red&attribute=12&catalog=32', 'attribute'), '&attribute=12');
        assert.equal(url.getGetParam('&option[4][]=red&attribute=&catalog=32', 'attribute'), '&attribute=');
        assert.equal(url.getGetParam('&option[4][]=red&attribute=&catalog=32', 'no_exists'), '');
        assert.equal(url.getGetParam('&attribute=test&option[4][]=red&catalog=32', 'attribute'), '&attribute=test');
        assert.equal(url.getGetParam('&option[4][]=red&catalog=32&attribute=test', 'attribute'), '&attribute=test');
        assert.equal(url.getGetParam('&option[4][]=red&option[5][]=blue&catalog=32&attribute=test&attribute=test', 'option'), '&option[4][]=red&option[5][]=blue');
    });
});

describe("url.getGetParamValue", function() {
    it("return $_GET param value string|array", function () {

        var expectedArray = [];
        expectedArray[4] = ['red'];
        expectedArray[5] = ['blue'];

        assert.equal(url.getGetParamValue('&option[4][]=red&attribute=12&catalog=32', 'attribute'), '12');
        assert.equal(url.getGetParamValue('&option[4][]=red&attribute=&catalog=32', 'attribute'), '');
        assert.equal(url.getGetParamValue('&option[4][]=red&attribute=&catalog=32', 'no_exists'), '');
        assert.equal(url.getGetParamValue('&attribute=test&option[4][]=red&catalog=32', 'attribute'), 'test');
        assert.equal(url.getGetParamValue('&option[4][]=red&catalog=32&attribute=test', 'attribute'), 'test');
        assert.equal(url.getGetParamValue('&option[4][]=red&catalog=32&attribute=first&attribute=second', 'attribute'), 'second');

        var data = '&option[4][]=red&option[5][]=blue&catalog=32&attribute=test&attribute=test';
        if (assertArray.assertArray(url.getGetParamValue(data, 'option'), expectedArray)) {
            assert.isOk(true);
        } else {
            expect(url.getGetParamValue(data)).to.equal(expectedArray)
        }
    });
});

describe("url.cutGetParam", function() {
    it("return string without input param $_GET params", function () {
        assert.equal(url.cutGetParam('&option[4][]=red&attribute=12&catalog=32', 'attribute'), '&option[4][]=red&catalog=32');
        assert.equal(url.cutGetParam('&option[4][]=red&attribute=&catalog=32', 'attribute'), '&option[4][]=red&catalog=32');
        assert.equal(url.cutGetParam('&option[4][]=red&attribute=&catalog=32', 'no_exists'), '&option[4][]=red&attribute=&catalog=32');
        assert.equal(url.cutGetParam('&attribute=test&option[4][]=red&catalog=32', 'attribute'), '&option[4][]=red&catalog=32');
        assert.equal(url.cutGetParam('&option[4][]=red&catalog=32&attribute=test', 'attribute'), '&option[4][]=red&catalog=32');
        assert.equal(url.cutGetParam('&option[4][]=red&option[5][]=blue&catalog=32&attribute=test&attribute=test', 'option'), '&catalog=32&attribute=test&attribute=test');
        assert.equal(url.cutGetParam('&option[4][]=red&option[5][]=blue&catalog=32&option[6][]=blue&attribute=test&option[7][]=blue&attribute=test', 'option'), '&catalog=32&attribute=test&attribute=test');
    });
});