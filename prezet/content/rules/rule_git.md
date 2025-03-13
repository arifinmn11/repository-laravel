---
title: Rule Commit
date: 2024-05-07
category: Features
excerpt: Learn how to enhance your Markdown content with dynamic Blade components in Prezet.
image: /prezet/img/ogimages/features-blade.webp
---
# Commit Message Format

A commit message should be structured as follows:

```
type(scope): subject

body

footer
```

## Types

- **feat:** A new feature
- **fix:** A bug fix
- **docs:** Documentation changes
- **style:** Code style changes (formatting, missing semi-colons, etc)
- **refactor:** Code refactoring
- **test:** Adding or modifying tests
- **chore:** Maintenance tasks

## Rules

- Subject line should be less than 50 characters
- Begin subject line with a capital letter
- Do not end subject line with a period
- Use imperative mood in subject line
- Separate subject from body with a blank line
- Wrap body at 72 characters

## Examples

```
feat(auth): Add OAuth2 authentication

Implement OAuth2 authentication flow using Google provider
- Add login/logout functionality
- Store user tokens securely
- Handle token refresh

Closes #123
```

```
fix(api): Resolve user data fetch timeout

Increase request timeout from 5s to 15s
Add retry mechanism for failed requests

Fixes #456
```

## Best Practices

- Keep commits atomic - one logical change per commit
- Write descriptive messages that explain why the change was made
- Reference issue numbers when applicable
- Use consistent formatting across the team