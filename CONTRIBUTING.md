# Contributing Guide: Branch Creation and Merge Requests

This guide explains how to create a branch and submit a merge request for the Boardgame Cafe Reservation project.

## Prerequisites

- Git installed on your machine
- Access to the repository
- An account on the Git platform (GitHub, GitLab, etc.)
- Your repository cloned locally

## Workflow: Creating a Branch and Submitting a Merge Request

### Step 1: Update Your Local Repository

Before creating a new branch, ensure your local repository is up to date:

```bash
git checkout main
git pull origin main
```

### Step 2: Create a New Branch

Create a new branch with a descriptive name. Use one of these naming conventions:

- **Feature**: `feature/feature-name`
- **Bug Fix**: `bugfix/bug-name`
- **Improvement**: `improvement/improvement-name`
- **Documentation**: `docs/doc-name`

Example:

```bash
git checkout -b feature/add-reservation-calendar
```

### Step 3: Make Your Changes

Edit files, add features, or fix bugs on your new branch.

### Step 4: Commit Your Changes

Make meaningful commits with clear messages:

```bash
git add .
git commit -m "Add reservation calendar feature"
```

**Commit Message Guidelines:**
- Use present tense ("add" not "added")
- Be descriptive and concise
- Reference issue numbers if applicable: `#123`

Example good commit messages:
- `Add user authentication to reservation form`
- `Fix: resolve calendar date selection bug #45`
- `Docs: update installation instructions`

### Step 5: Push Your Branch

Push your branch to the remote repository:

```bash
git push origin feature/add-reservation-calendar
```

### Step 6: Submit a Merge Request

After pushing your branch, follow these steps:

#### For GitHub:
1. Go to the repository on GitHub
2. Click the **"Compare & pull request"** button (appears after pushing)
3. Fill in the pull request form:
   - **Title**: Clear, concise summary of changes
   - **Description**: Explain what changed and why
   - **Reviewers**: Add team members for review
4. Click **"Create Pull Request"**

### Step 7: Merge Your Changes

Once approved:
1. Review has been completed and approved
2. All checks pass
3. Click **"Merge"** (or **"Merge pull request"** on GitHub)
4. Optionally delete the branch after merging

## Best Practices (REFERENCE ONLY)

### Branch Naming
- Use lowercase letters and hyphens
- Keep names concise but descriptive
- Avoid special characters

### Commit Messages
- Start with a verb (Add, Fix, Update, Remove, etc.)
- Limit first line to 50 characters
- Add detailed explanation in the body if needed
- Reference related issues

### Code Quality
- Follow the project's coding standards
- Test your changes locally
- Keep commits logical and focused
- Avoid committing unrelated changes

### Merge Request Description
Include:
- What was changed
- Why it was changed
- How to test the changes
- Any breaking changes
- Links to related issues

## Example Merge Request Description

```markdown
## Description
Adds a calendar widget to the reservation booking page, allowing users to visually select dates.

## Changes
- Created ReservationCalendar component
- Updated ReservationController to handle calendar data
- Added calendar styling with Tailwind CSS

## How to Test
1. Navigate to the reservation page
2. Click on a date in the new calendar widget
3. Verify the date is selected and displayed correctly

## Related Issues
Closes #123
```

## Common Commands Reference

```bash
# Create and switch to new branch
git checkout -b feature/branch-name

# List all branches
git branch -a

# Switch between branches
git checkout branch-name

# Check current branch and status
git status

# View commit history
git log --oneline

# Undo changes to a file
git checkout -- filename

# Undo last commit (keep changes)
git reset --soft HEAD~1

# Delete a local branch
git branch -d branch-name

# Delete a remote branch
git push origin --delete branch-name

# Fetch latest changes from remote
git fetch origin

# Rebase your branch on main (after updates)
git rebase origin/main
```

## Need Help?

- Check existing issues and pull requests
- Review project documentation
- Ask team members in discussions or comments
- Check git documentation: `git help <command>`

## Code Review Checklist

Before submitting, ensure:
- [ ] Code follows project standards
- [ ] Tests are added or updated
- [ ] Documentation is updated if needed
- [ ] No merge conflicts exist
- [ ] All tests pass locally
- [ ] Commit messages are clear
- [ ] No debugging code or console logs remain

---

Happy contributing! 🎉
