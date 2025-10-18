# ==============================================================================
# General Commands
# ==============================================================================

.PHONY: help
help: ## ❓ Display this help screen.
	@printf "\033[33mUsage:\033[0m\n make [target] [arg=\"val\"...]\n\n\033[33mTargets:\033[0m\n"
	@grep -E '^[-a-zA-Z0-9_\.\/]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "  \033[32m%-32s\033[0m %s\n", $$1, $$2}'

# ==============================================================================
# Coding Style
# ==============================================================================

.PHONY: coding-style
coding-style: coding-style/check ## 👮 Alias to check for coding style violations.

.PHONY: coding-style/check
coding-style/check: coding-style/check/php-cs-fixer coding-style/check/rector ## 👀 Check for violations using all coding style tools (dry-run).

.PHONY: coding-style/check/php-cs-fixer
coding-style/check/php-cs-fixer: ## -- 🧐 Preview PHP-CS-Fixer changes without applying them.
	vendor/bin/php-cs-fixer fix --diff --dry-run -vv

.PHONY: coding-style/check/rector
coding-style/check/rector: ## -- 🧐 Preview Rector refactorings without applying them.
	vendor/bin/rector process --dry-run

.PHONY: coding-style/format
coding-style/format: coding-style/format/php-cs-fixer coding-style/format/rector ## ✨ Automatically fix all coding style violations.

.PHONY: coding-style/format/php-cs-fixer
coding-style/format/php-cs-fixer: ## -- 🎨 Automatically apply PHP-CS-Fixer fixes.
	vendor/bin/php-cs-fixer fix --diff -vv

.PHONY: coding-style/format/rector
coding-style/format/rector: ## -- 🛠️ Automatically apply Rector refactorings.
	vendor/bin/rector process

# ==============================================================================
# Static Analysis
# ==============================================================================

.PHONY: static-analysis
static-analysis: static-analysis/phpstan ## 🔎 Run all static analysis tools.

.PHONY: static-analysis/phpstan
static-analysis/phpstan: ## -- 🐛 Analyze code for potential bugs using PHPStan.
	vendor/bin/phpstan analyse --memory-limit=-1

# ==============================================================================
# Testing
# ==============================================================================

.PHONY: tests
tests: tests/unit ## ✅ Run all test suites.

.PHONY: tests/unit
tests/unit: ## -- 🧪 Run PHPUnit tests.
	XDEBUG_MODE=coverage vendor/bin/phpunit
