## 2024-02-24

### Changed
- Refactored conditions in several GitHub workflow steps to ensure accurate execution flow. This change enhances the reliability and efficiency of the CI/CD pipeline by aligning execution conditions with the expected workflow progression.
- Improved the readability and maintainability of the workflow file by ensuring conditionals are logically structured and directly relevant to their respective steps.
- Enhanced the GitHub Actions workflow to support testing against multiple PHP versions ('8.2' and '8.3') using a matrix strategy, improving compatibility testing across different PHP environments.
- Adjusted the 'release' job in the GitHub Actions workflow to depend on the 'build' job's success, ensuring more reliable releases based on thoroughly vetted builds.

## 2024-02-21

### Changed
- Updated several development dependencies in the `composer.json` file to ensure compatibility and stability with the latest versions. The updates include `infection/infection`, `laravel/pint`, and `phpstan/phpstan`. This change contributes to the project's overall quality and security.

## 2024-02-18

### Added
- Introduced a new step in the GitHub Actions workflow for PHP to upload code coverage reports to Codecov. This enhancement will allow tracking of code coverage statistics for the project.

## 2024-02-18

### Changed
- Enhanced documentation in the `searchWords` function in `StringManipulation.php` for improved code readability. The added comments explain the function's operations such as null-checking, applying name-fixing standards, replacing special characters and underscores, removing accents, and reducing spaces.