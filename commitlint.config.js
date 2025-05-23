module.exports = {
  extends: [
    "@commitlint/config-conventional"
  ],
  parserPreset: 'conventional-changelog-conventionalcommits',
  rules: {
    "body-leading-blank": [
      2,
      "always"
    ],
    "body-max-line-length": [
      2,
      "always",
      72
    ],
    "body-min-length": [
      1,
      "always",
      20
    ],
    "body-empty": [
      1,
      "never"
    ],
    "body-case": [
      0,
      "always",
      "sentence-case"
    ],
    "footer-leading-blank": [
      2,
      "always"
    ],
    "footer-max-line-length": [
      2,
      "always",
      72
    ],
    "header-max-length": [
      2,
      "always",
      72
    ],
    "header-min-length": [
      2,
      "always",
      10
    ],
    "header-trim": [
      2,
      "always"
    ],
    "scope-case": [
      2,
      "always",
      "lower-case"
    ],
    "scope-empty": [
      1,
      "never"
    ],
    "scope-min-length": [
      1,
      "always",
      2
    ],
    "scope-max-length": [
      1,
      "always",
      20
    ],
    "subject-case": [
      2,
      "always",
      "sentence-case"
    ],
    "subject-empty": [
      2,
      "never"
    ],
    "subject-exclamation-mark": [
      2,
      "never"
    ],
    "subject-full-stop": [
      2,
      "never",
      "."
    ],
    "subject-max-length": [
      2,
      "always",
      50
    ],
    "subject-min-length": [
      2,
      "always",
      10
    ],
    "type-case": [
      2,
      "always",
      "lower-case"
    ],
    "type-empty": [
      2,
      "never"
    ],
    "type-enum": [
      2,
      "always",
      [
        "feat",
        "fix",
        "docs",
        "style",
        "refactor",
        "perf",
        "test",
        "build",
        "ci",
        "chore",
        "revert",
        "security"
      ]
    ],
    "type-min-length": [
      2,
      "always",
      3
    ],
    "type-max-length": [
      2,
      "always",
      10
    ],
    "signed-off-by": [
      2,
      "always",
      "Signed-off-by: Marjo Wenzel van Lier <marjo.vanlier@gmail.com>"
    ],
    "trailer-exists": [
      2,
      "always",
      "Signed-off-by:"
    ]
  }
};
