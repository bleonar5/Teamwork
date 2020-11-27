var Cryptography = class Cryptography {

  constructor(mapping) {
    this.mapping = mapping;
    this.operators = ['-', '+'];
    this.allowedChars = mapping.concat(this.operators);
  }

  getMapping() {
    return this.mapping;
  }

  getAllowedChars() {
    return this.allowedChars;
  }

  parseEquation(eq) {
    eq = eq.replace(/\s+/g, '');
    eq = eq.toUpperCase();
    eq = eq.trim();
    var arr = eq.split('');

    function parse(eq, allowedChars, mapping) {

      for(var i = 0; i < eq.length; i++) {
        var x = allowedChars.indexOf(eq[i]);
        if(allowedChars.indexOf(eq[i]) >= 0) {
          if(mapping.indexOf(eq[i]) >= 0)
            eq[i] = x;
        }
        else throw new Error("'" + eq[i] + "' is not allowed. Write an equation using only the letters A to J, and the '+' or '-' symbols.");
      }

      // We need to get rid of leading zeros, they will cause eval to error
      var eqTranslated = eq.join('');
      var eqNums = eqTranslated.split(/[+-]+/);
      eqNums.forEach(function(num) {
        eqTranslated = eqTranslated.replace(num.toString(), parseInt(num));
      })
      return eqTranslated;
    }

    var parsed = parse(arr, this.allowedChars, this.mapping);

    var n = eval(parsed);

    var answer = '';
    var sign = '';

    if(n == 0) return this.mapping[n];

    if(n < 0) {
      sign = '-';
      n = Math.abs(n);
    }

    while(n > 0) {

      answer = this.mapping[n % 10] + answer;
      n = parseInt(n / 10);
    }

    return sign + answer;

  }

  testHypothesis(key, val) {
    return this.mapping.indexOf(key) == val;
  }
}
