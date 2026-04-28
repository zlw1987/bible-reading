# Project Improvement Suggestions

This review focuses on security, reliability, and maintainability improvements based on a quick audit of the current PHP pages.

## 1) Fix high-risk authentication and session issues (highest priority)

- **Do not trust the `rememberme` cookie as a standalone authentication requirement.** `judgelogin.php` currently redirects to login when the cookie is missing, even when a valid session exists. This makes session behavior brittle and encourages over-reliance on a client cookie.
- **Replace custom cookie encryption with a server-side token model.** The current cookie encryption stores the encryption key together with ciphertext and IV, which means anyone who can read or forge the cookie can impersonate a user. Use a random opaque token stored server-side (DB table with hash, expiry, device metadata).
- **Regenerate session IDs on login** to prevent session fixation (`session_regenerate_id(true)` immediately after successful authentication).
- **Set secure cookie attributes** (`HttpOnly`, `Secure`, `SameSite=Lax/Strict`) for auth/session cookies.

## 2) Remove SQL injection and authorization risks

- Several endpoints still use string interpolation with untrusted input (`$_GET`/cookie-derived values), for example plan signup and plan queries. Convert these to prepared statements everywhere.
- `signup_plan.php` accepts both `plan` and `user` from query parameters. The server should derive user ID from the authenticated session and ignore user IDs from the URL to prevent horizontal privilege escalation.

## 3) Add output escaping to reduce XSS exposure

- Multiple pages echo values from DB/query parameters directly into HTML links/text (plan names, descriptions, user names, dates).
- Apply `htmlspecialchars(..., ENT_QUOTES, 'UTF-8')` for all dynamic output unless intentionally outputting trusted HTML.

## 4) Modernize compatibility and reduce production surprises

- Replace short open tags `<?` with `<?php` throughout the project. On environments where `short_open_tag` is disabled, pages can break.
- Avoid deprecated/error-prone patterns like `mysqli_affected_rows` for `SELECT` counts; use `mysqli_num_rows` or explicit `COUNT(*)` queries.
- Remove `or die(mysqli_error())` from production paths; centralize error handling and logging to avoid leaking DB internals.

## 5) Improve form security and UX

- Add CSRF protection for state-changing actions (signup, comments, delete comment).
- For destructive actions (delete endpoints), require POST + CSRF token instead of GET links.
- Improve password policy (length/complexity checks) and rate-limit login attempts to mitigate brute-force attacks.

## 6) Codebase structure improvements

- Introduce a small shared bootstrap/config file (DB init, timezone, session setup, common helpers).
- Create helper functions for DB access and escaping to reduce repeated boilerplate and inconsistent security handling.
- Consider a simple routing/controller structure (even lightweight) to separate HTML rendering from request handling logic.

## 7) Delivery plan recommendation

1. **Security patch sprint**: auth cookie redesign, prepared statements, CSRF on mutating routes.
2. **Stability sprint**: short tag removal, standardized error logging, session/cookie hardening.
3. **Refactor sprint**: shared helpers, page decomposition, and test scaffolding.

## Quick wins you can implement first

- Lock `signup_plan.php` to current session user only.
- Convert vulnerable string-built SQL queries in login/judge/sign-up flows to prepared statements.
- Escape all rendered dynamic fields in `plan_page.php` and related pages.
- Replace `<?` tags in the most frequently used pages (`plan_page.php`, `signup_plan.php`, etc.).
