Question: What is the difference between Dependency Injection and using a Facade in Laravel? In which scenario would you prefer using Dependency Injection?
Answer: 
**Dependency Incjection** 
Injects required classes or interfaces directly into constructors or methods.Promotes loose coupling, explicit dependencies, and easier testing.Preferred when building maintainable and testable code
**Facade**
Provides a static-like shortcut to Laravel services. Convenient but hides dependencies and reduces testability.
**When to use DI**
When i need clear, testable, and decoupled code.When my class depends on multiple services.

Question:  In a real-world scenario with 10,000 products, how would you optimize the search query to ensure the page loads quickly?
Answer: Index columns that are frequently searched (e.g., name, sku).Paginate results to avoid loading all records at once. Select only required columns to reduce query size.Use full-text search or 
dedicated search engines like Elasticsearch for faster queries.Cache frequently accessed results to reduce repeated queries.

Question: How does Laravel's CSRF protection work with Inertia.js, and why is it important for form submissions?
Answer: Laravel uses a CSRF token to validate form submissions.Inertia.js or Axios must include this token in headers or form data.Middleware verifies the token for all POST, PUT, PATCH, and DELETE
requests.
It is important for submission because it prevents malicious sites from submitting forms on behalf of authenticated users.

Question:  Explain the N+1 query problem. If each Product belonged to a Category, how would you prevent N+1 issues when loading the list?
Answer:  It Occurs when each related model triggers a separate database query inside a loop. Leads to many unnecessary queries and poor performance.
I will be using  eager loading with with() to load all related models in a single query.
