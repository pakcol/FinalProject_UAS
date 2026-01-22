#!/bin/bash

# Quick Setup Script for FinalProject_UAS
# This script will reset local repo to match remote and run migrations

echo "🚀 Starting FinalProject_UAS Setup..."
echo ""

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Step 1: Backup uncommitted changes
echo -e "${YELLOW}📦 Checking for uncommitted changes...${NC}"
if [[ -n $(git status -s) ]]; then
    echo -e "${YELLOW}Stashing local changes...${NC}"
    git stash save "Auto-backup before setup $(date +%Y-%m-%d_%H-%M-%S)"
fi

# Step 2: Fetch latest from remote
echo -e "${YELLOW}📥 Fetching latest changes from GitHub...${NC}"
git fetch fork main

# Step 3: Reset to remote
echo -e "${RED}⚠️  Resetting local to match remote...${NC}"
git reset --hard fork/main

echo -e "${GREEN}✅ Git sync completed!${NC}"
echo ""

# Step 4: Install dependencies
echo -e "${YELLOW}📦 Installing Composer dependencies...${NC}"
composer install --no-interaction

echo -e "${GREEN}✅ Dependencies installed!${NC}"
echo ""

# Step 5: Run migrations
echo -e "${YELLOW}🗄️  Running migrations...${NC}"
php artisan migrate:fresh --seed --force

echo -e "${GREEN}✅ Database migrated!${NC}"
echo ""

# Step 6: Clear caches
echo -e "${YELLOW}🧹 Clearing caches...${NC}"
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

echo -e "${GREEN}✅ Caches cleared!${NC}"
echo ""

# Step 7: Show next steps
echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}✅ Setup completed successfully!${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""
echo -e "${YELLOW}📝 Next Steps:${NC}"
echo ""
echo "1. Start scheduler (in separate terminal):"
echo -e "   ${GREEN}php artisan schedule:work${NC}"
echo ""
echo "2. Start development server:"
echo -e "   ${GREEN}php artisan serve${NC}"
echo ""
echo "3. Test auto-generation commands:"
echo -e "   ${GREEN}php artisan game:generate-gold${NC}"
echo -e "   ${GREEN}php artisan game:produce-troops${NC}"
echo ""
echo "4. Access the game:"
echo -e "   ${GREEN}http://localhost:8000${NC}"
echo ""
echo "5. Admin panel:"
echo -e "   ${GREEN}http://localhost:8000/admin/login${NC}"
echo -e "   Email: ${YELLOW}admin@admin.com${NC}"
echo -e "   Pass:  ${YELLOW}admin123${NC}"
echo ""
