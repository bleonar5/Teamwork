var Cryptography = class Cryptography {
  

  ruleset;

  constructor(mapping,ruleset) {
    this.mapping = mapping;
    this.operators = ['-', '+'];
    this.allowedChars = mapping.concat(this.operators);
    this.ruleset = ruleset;
    console.log('yooo');
    console.log(rules);
  }

  getMapping() {
    return this.mapping;
  }

  getAllowedChars() {
    return this.allowedChars;
  }

  parseEquation(eq,round) {
    console.log('the round is '+round.toString());
    var sym_count = {
      'A':0,
      'B':0,
      'C':0,
      'D':0,
      'E':0,
      'F':0,
      'G':0,
      'H':0,
      'I':0,
      'J':0,
      '-':0,
      '+':0
    }
    eq = eq.replace(/\s+/g, '');
    eq = eq.toUpperCase();
    eq = eq.trim();
    var arr = eq.split('');
    console.log(arr);

    arr.forEach(function(value,i) {
      console.log(value,i);
      if(value in sym_count){
        sym_count[value.toString()] += 1;
      }
    });

    console.log(sym_count);

    var rule_broken = null;

    this.ruleset = new Array(this.ruleset[0],this.ruleset[1]);
    console.log('FUCK');
    console.log(this.ruleset);

  
    this.ruleset.forEach(function(rule,i){
      console.log('checking rule '+rule.toString());
      console.log(' round == 1: '+ (round ==1).toString());
      switch(rule){
        case 1:
          console.log(sym_count['A']+sym_count['B']+sym_count['C']+sym_count['D']+sym_count['E']+sym_count['F']+sym_count['G']+sym_count['H']+sym_count['I']+sym_count['J'])
          console.log(round);
          if(sym_count['A']+sym_count['B']+sym_count['C']+sym_count['D']+sym_count['E']+sym_count['F']+sym_count['G']+sym_count['H']+sym_count['I']+sym_count['J'] > 4 && round == 1)
            rule_broken = rule;
          break;
        case 2:
          console.log(sym_count['A']+sym_count['B']+sym_count['C']+sym_count['D']+sym_count['E']+sym_count['F']+sym_count['G']+sym_count['H']+sym_count['I']+sym_count['J'])
          console.log(round);
          if(sym_count['A']+sym_count['B']+sym_count['C']+sym_count['D']+sym_count['E']+sym_count['F']+sym_count['G']+sym_count['H']+sym_count['I']+sym_count['J'] < 3 && round == 1)
            rule_broken = rule;
          break;
        case 3:
          console.log(sym_count['-']);
          if (sym_count['-'] == 0 && round == 1)
            rule_broken = rule;
          break;
        case 4:
          console.log(sym_count['F']);
          if (sym_count['F'] == 0 && round == 2)
            rule_broken = rule;
          break;
        case 5:
          console.log(sym_count['G']);
          if (sym_count['G'] == 0 && round == 2)
            rule_broken = rule;
          break;
        case 6:
          console.log(sym_count['H']);
          if (sym_count['H'] == 0 && round == 2)
            rule_broken = rule;
          break;
        case 7:
          console.log(sym_count['I']);
          if (sym_count['I'] == 0 && round == 2)
            rule_broken = rule;
          break;
        case 8:
          console.log(sym_count['A']);
          if (sym_count['A'] > 0 && round == 3)
            rule_broken = rule;
          break;
        case 9:
          if (sym_count['B'] > 0 && round == 3)
            rule_broken = rule;
          break;
        case 10:
          if (sym_count['C'] > 0 && round == 3)
            rule_broken = rule;
          break;
        case 11:
          if (sym_count['D'] > 0 && round == 3)
            rule_broken = rule;
          break;
        case 12:
          if (sym_count['-'] == 0 && round == 4)
            rule_broken = rule;
          break;
        case 13:
          if (sym_count['-'] > 0 && round == 4)
            rule_broken = rule;
          break;
        case 14:
          if (sym_count['-'] == 0 && round == 5)
            rule_broken = rule;
          break;
        case 15:
          if (sym_count['-'] > 0 && round == 5)
            rule_broken = rule;
          break;
        default:
          break;
      }
    });



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

    return [sign + answer,rule_broken];

  }

  testHypothesis(key, val) {
    return this.mapping.indexOf(key) == val;
  }
}
