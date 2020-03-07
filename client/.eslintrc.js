module.exports = {
  "root": true,
  "env": {
    "es6": true,
    "browser": true
  },
  "extends": [
    "eslint:recommended",
    "plugin:react/recommended"
  ],
  "plugins": [
    "react",
  ],
  "parser": "babel-eslint",
  "parserOptions": {
    "sourceType": "module",
    "ecmaVersion": 2018,
    "ecmaFeatures": {
      "jsx": true,
    },
  },
  "ignorePatterns": ["node_modules/"],
  "settings": {
    "react": {
      "version": "16.13.0"
    },
  },
  "rules": {
    "indent": ["error", 4, {
      //any number here for props will cause multiplication with parent `indent` property
      "VariableDeclarator": "first",
      "MemberExpression": 2,
      // "ImportDeclaration": "first",
      // "ObjectExpression": 1,
      // "FunctionDeclaration": {"parameters": "first"},
      // "FunctionExpression": {"parameters": "first"},
      // "CallExpression": {"arguments": "first"},
      "SwitchCase": 1,
    }],
    "no-mixed-spaces-and-tabs": "error",
    "quotes": ["error", "single"],
    "jsx-quotes": ["error", "prefer-single"],
    "linebreak-style": ["error", "unix"], // can be set to "off" for Windows usage, but who cares?
    "comma-dangle": ["error", "always-multiline"],
    "comma-spacing": "error",
    "semi": ["error", "always"],
    "semi-style": "error",
    "semi-spacing": ["error", {
      "before": false,
      "after": true,
    }],
    "eqeqeq": ["error", "always"],
    "key-spacing": ["error", {
      "align": "value",
    }],
    "max-len": ["error", {
      "code": 120, // default = 80, maximum length of the line
      "ignoreComments": false,
      "ignoreTrailingComments": true,
    }],
    "no-underscore-dangle": ["error", {
      "allowAfterThis": true, // use 'this.__proto__ = ()' to redefine such functions
    }],
    "object-curly-spacing": ["error", "always"],
    "object-property-newline": "error",
    "sort-imports": ["error", {
      "memberSyntaxSortOrder": ["single", "multiple", "all", "none"],
    }],
    "arrow-body-style": ["error", "as-needed"],
    "arrow-parens": ["error", "as-needed"],
    "arrow-spacing": "error",
    "constructor-super": "error",
    "react/boolean-prop-naming": "warn",
    "react/no-array-index-key": "warn",
    "react/prefer-es6-class": ["error", "always"],
    "react/prop-types": "off", // disabled temporary, later must be enabled and check any props via PropTypes
    "react/self-closing-comp": ["error", {
      "component": true,
      "html": false,
    }],
    "react/sort-comp": "error",
    "react/sort-prop-types": "warn",
    "react/jsx-closing-bracket-location": ["error", "line-aligned"],
    "react/jsx-closing-tag-location": "error",
    "react/jsx-curly-brace-presence": ["error", "ignore"],
    "react/jsx-curly-newline": "error",
    "react/jsx-curly-spacing": ["error", "always", {
      "allowMultiline": false,
    }],
    "react/jsx-equals-spacing": ["error", "never"],
    "react/jsx-first-prop-new-line": ["error", "always"],
    "react/jsx-handler-names": "warn", // 'warn' temporary, later 'error'
    "react/jsx-indent": ["error", 4],
    "react/jsx-indent-props": ["error", 4],
    "react/jsx-key": "error",
    "react/jsx-max-props-per-line": "error",
    "react/jsx-no-duplicate-props": "error",
    "react/jsx-no-literals": ["error", {
      "noStrings": false,
    }], // TODO: check this prop to be actual to use
    "react/jsx-one-expression-per-line": ["error", {
      "allow": "literal",
    }],
    "react/jsx-pascal-case": "error",
    "react/jsx-sort-props": ["error", {
      "callbacksLast": true,
      "reservedFirst": true,
      "noSortAlphabetically": false,
    }],
    "react/jsx-tag-spacing": ["error", {
      "closingSlash": "never",
      "beforeSelfClosing": "always",
      "afterOpening": "never",
      "beforeClosing": "never",
    }],
    "react/jsx-wrap-multilines": ["warn", {
      "declaration": "parens-new-line",
      "assignment": "parens-new-line",
      "return": "parens-new-line",
      "arrow": "parens-new-line",
      "prop": "parens-new-line",
    }],
    "require-jsdoc": "off",
  }
};
