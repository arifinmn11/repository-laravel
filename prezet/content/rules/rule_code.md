---
title: Rule Code
date: 2024-05-08
category: Features
excerpt: Prezet is a markdown powered blogging platform that is easy to use and customize.
image: /prezet/img/ogimages/features-markdown.webp
---
# PHP Clean Code Rules

## 1. Naming Conventions

- **Class Names:** Use PascalCase and should be nouns
    
    ```php
    class UserController
    class PaymentService
    class OrderRepository
    ```
    
- **Method/Function Names:** Use camelCase and should be verbs
    
    ```php
    public function getUserById()
    public function createOrder()
    public function validatePayment()
    ```
    
- **Variable Names:** Use camelCase and should be descriptive
    
    ```php
    $firstName
    $orderTotal
    $isActive
    ```
    
- **Constant Names:** Use UPPER_CASE with underscores
    
    ```php
    const MAX_ATTEMPTS = 3;
    const API_KEY = 'xyz';
    ```
    

## 2. Function Rules

- **Single Responsibility:** Functions should do one thing only
- **Keep Functions Small:** Ideally under 20 lines of code
- **Maximum Arguments:** Try to keep it under 3 parameters

```php
// Good
public function calculateTotal(float $price, float $tax): float
{
    return $price * (1 + $tax);
}

// Bad - Too many parameters
public function createUser($name, $email, $password, $address, $phone, $role, $department)
```

## 3. Control Structures

- **Prefer Early Returns:** Reduce nesting levels

```php
// Good
public function processUser($user)
{
    if (!$user->isActive()) {
        return false;
    }

    // Process user
    return true;
}

// Bad
public function processUser($user)
{
    if ($user->isActive()) {
        // Nested code
        if ($user->hasPermission()) {
            // More nested code
        }
    }
}
```

## 4. Loop Best Practices

```php
// Good - foreach for arrays
foreach ($users as $user) {
    // Process user
}

// Good - for when you need index
for ($i = 0; $i < count($items); $i++) {
    // Process with index
}

// Good - while for unknown iterations
while ($isProcessing) {
    // Process something
}
```

## 5. Error Handling

```php
// Use try-catch blocks for error handling
try {
    $this->userService->createUser($data);
} catch (ValidationException $e) {
    // Handle validation errors
    Log::error($e->getMessage());
    throw new CustomException('User creation failed');
} catch (Exception $e) {
    // Handle unexpected errors
    Log::error($e->getMessage());
    throw $e;
}
```

## 6. Class Organization

- **Order of Elements:**1. Constants2. Properties3. Constructor4. Public methods5. Protected methods6. Private methods

```php
class UserService
{
    // Constants
    private const MAX_ATTEMPTS = 3;

    // Properties
    private $userRepository;

    // Constructor
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    // Public methods
    public function getUser()
    {
        // Implementation
    }

    // Protected methods
    protected function validateUser()
    {
        // Implementation
    }

    // Private methods
    private function hashPassword()
    {
        // Implementation
    }
}
```

## 7. Comments and Documentation

- **Use DocBlocks for classes and methods**

```php
/**
 * Handles user-related operations
 *
 * @package App\Services
 */
class UserService
{
    /**
     * Create a new user in the system
     *
     * @param array $data User data
     * @return User
     * @throws ValidationException
     */
    public function createUser(array $data): User
    {
        // Implementation
    }
}
```

## 8. General Practices

- **Use Type Hints:** Always specify return types and parameter types
- **Avoid Magic Numbers:** Use constants for numbers with meaning
- **Keep Classes Small:** Follow Single Responsibility Principle
- **Use Dependency Injection:** Avoid creating dependencies inside classes
- **Follow PSR Standards:** Adhere to PHP-FIG coding standards


[table](https://tree.nathanfriend.io/?s=(%27options!(%27fancy!true~fullPath!false~trailingSlash!true~rootDot!false)~B(%27B%27storageGprezet6.obsidianH...6content6E7draft787seo7customizeH*routes7*frontmatter7E-views7*controllersF6C*8-20240509210223449.webpHogCSUMMARYF%27)~version!%271%27)*%20%206G*7FH8markdownBsource!Cimages6E*bladeF.mdG%5Cn*H6*%01HGFECB876*)
