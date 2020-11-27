var taskFunctions = {
    t1: function(x) {

      return 100 * ( Math.sin( x / 30 ) + Math.pow( ( x / 300 ), 2 ) );
    },

    t2: function(x) {
      return -10 + (100 * ( Math.sin( x / 50 )) + (Math.pow(x, 2) / 500));
    },

    a: function(x) {
      return 40 * ( Math.sin( x / 15 ) ) + 100 * ( Math.cos( x / 60 ) ) + ( x / 10);
    },

    b: function(x) {

      return 150 - ( Math.pow( (150 - x) , 2 ) / 150 ) + 100 * ( Math.cos( x / 20 ) );
    },

    c: function(x) {
      return 10 + (x/3) - (150 * Math.pow( Math.E, -(Math.pow((x - 245) / 15, 2))));
    },

    d: function(x) {
      return 40 * ( Math.sin( (x - 20) / 15 ) ) + 100 * ( Math.cos( (x - 20) / 15 ) ) + ( x / 3);
    },

    e: function(x) {
      return 100 - (x/3) - (50 * Math.pow( Math.E, -(Math.pow((x - 50) / 15, 2)))) + (150 * Math.pow( Math.E, -(Math.pow((x - 245) / 15, 2))));
    },

    f: function(x) {
      return 100 + (50 * Math.pow( Math.E, -(Math.pow((x - 50) / 15, 2)))) + (100 * Math.pow( Math.E, -(Math.pow((x - 150) / 15, 2)))) + (150 * Math.pow( Math.E, -(Math.pow((x - 275) / 15, 2))));
    },

    g: function(x) {
      return -50 - (x/5) + (40 * Math.pow( Math.E, -(Math.pow((x - 270) / 30, 2))))
    },

    h: function(x) {
      return (x/4) + (100 * Math.pow( Math.E, -(Math.pow((x - 50) / 15, 2)))) + (50 * Math.pow( Math.E, -(Math.pow((x - 150) / 10, 2))))
      - (50 * Math.pow( Math.E, -(Math.pow((x - 250) / 30, 2)))) - (50 * Math.pow( Math.E, -(Math.pow((x - 300) / 15, 2))));
    },

    i: function(x) {
      return (x / 4) + Math.pow( Math.E, -(Math.pow((x - 50) / 15, 2))) + (100 * Math.cos( x / 10 )) + (100 * (Math.sin( x / 30 )));
    },

    j: function(x) {
      return 80 *  Math.pow( Math.E, -(Math.pow((x - 50) / 20, 2))) + ((x / 2) - (200 * Math.pow( Math.E, -(Math.pow((x - 280) / 10, 2))))) + (30 * (Math.cos( x / 30 )));
    },

    k: function(x) {
      return 30 * Math.exp( -(Math.pow( x / 30, 2)) ) + (100 * Math.exp( -(Math.pow( (x - 150) / 30, 2 )) )) + (25 * Math.cos(x/40)) + (50 * Math.sin((x+35)/ 20));
    },

    l: function(x) {
      return 30 *  Math.cos(x/15) + (60 * Math.sin(x / 40)) + (50 * Math.cos((x - 20) / 10))
    },


};

// http://laktak.github.io/js-graphy/
