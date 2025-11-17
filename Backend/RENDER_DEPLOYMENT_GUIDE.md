# Render Deployment Guide for AgroSite Backend

This guide will help you deploy the AgroSite Laravel backend to Render.

## Prerequisites

- [ ] Git repository with your code
- [ ] Render account (free tier available)
- [ ] Basic understanding of Laravel and environment variables

## Quick Start

### 1. Prepare Your Repository

Ensure your repository has:
- ✅ All Laravel files
- ✅ `composer.json` with dependencies
- ✅ `.gitignore` (`.env` should be ignored)
- ✅ `render.yaml` (optional, for automated setup)

### 2. Create Database on Render

1. Log in to [Render Dashboard](https://dashboard.render.com)
2. Click **"New +"** → **"PostgreSQL"** or **"MySQL"**
3. Configure:
   - **Name:** `agrosite-db`
   - **Database:** `agrosite_db`
   - **User:** `agrosite_user`
   - **Plan:** Free (or paid for better performance)
4. Click **"Create Database"**
5. **Save the connection details** (you'll need them later)

### 3. Create Web Service

1. In Render dashboard, click **"New +"** → **"Web Service"**
2. Connect your Git repository
3. Configure the service:

   **Basic Settings:**
   - **Name:** `agrosite-api`
   - **Environment:** `PHP`
   - **Region:** Choose closest to your users
   - **Branch:** `main` (or your production branch)
   - **Root Directory:** `Backend` (if your repo has a Backend folder)

   **Build Settings:**
   - **Build Command:**
     ```bash
     composer install --no-dev --optimize-autoloader && php artisan config:cache && php artisan route:cache && php artisan view:cache
     ```
   - **Start Command:**
     ```bash
     php artisan serve --host=0.0.0.0 --port=$PORT
     ```

### 4. Configure Environment Variables

In your web service settings, go to **"Environment"** and add:

#### Required Variables

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-app-name.onrender.com
FRONTEND_URL=https://your-frontend-domain.com

DB_CONNECTION=mysql
DB_HOST=<your-db-host>
DB_PORT=3306
DB_DATABASE=agrosite_db
DB_USERNAME=<your-db-username>
DB_PASSWORD=<your-db-password>
```

#### Optional Variables

```env
LOG_CHANNEL=stderr
LOG_LEVEL=error
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
FILESYSTEM_DRIVER=local

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=<your-mail-username>
MAIL_PASSWORD=<your-mail-password>
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@agrosite.com
MAIL_FROM_NAME=AgroSite API

SANCTUM_STATEFUL_DOMAINS=your-frontend-domain.com
```

**Note:** Replace placeholders with actual values from your database service.

### 5. Deploy

1. Click **"Create Web Service"**
2. Render will start building your application
3. Monitor the build logs
4. Wait for deployment to complete (usually 2-5 minutes)

### 6. Run Migrations

After first deployment:

1. Go to your service dashboard
2. Click **"Shell"** tab
3. Run:
   ```bash
   php artisan migrate --force
   php artisan db:seed
   ```

### 7. Verify Deployment

Test your API:
```bash
curl https://your-app.onrender.com/api/products
```

## Detailed Configuration

### Using render.yaml (Recommended)

If you have `render.yaml` in your repository root, Render can auto-configure your services:

1. Push `render.yaml` to your repository
2. In Render dashboard, click **"New +"** → **"Blueprint"**
3. Connect your repository
4. Render will read `render.yaml` and create services automatically

### Manual Configuration

If not using `render.yaml`, follow the manual steps above.

## Environment Variables Reference

### Application

| Variable | Description | Example |
|----------|-------------|---------|
| `APP_ENV` | Environment | `production` |
| `APP_DEBUG` | Debug mode | `false` |
| `APP_KEY` | Application key | Auto-generated |
| `APP_URL` | Your Render app URL | `https://agrosite-api.onrender.com` |

### Database

| Variable | Description | Source |
|----------|-------------|--------|
| `DB_CONNECTION` | Database type | `mysql` |
| `DB_HOST` | Database host | From database service |
| `DB_PORT` | Database port | `3306` |
| `DB_DATABASE` | Database name | From database service |
| `DB_USERNAME` | Database user | From database service |
| `DB_PASSWORD` | Database password | From database service |

### Frontend

| Variable | Description | Example |
|----------|-------------|---------|
| `FRONTEND_URL` | Frontend domain | `https://agrosite.com` |
| `SANCTUM_STATEFUL_DOMAINS` | Auth domains | `your-frontend-domain.com` |

### Mail (Optional)

| Variable | Description | Example |
|----------|-------------|---------|
| `MAIL_MAILER` | Mail driver | `smtp` |
| `MAIL_HOST` | SMTP host | `smtp.mailtrap.io` |
| `MAIL_PORT` | SMTP port | `2525` |
| `MAIL_USERNAME` | SMTP username | Your mail username |
| `MAIL_PASSWORD` | SMTP password | Your mail password |
| `MAIL_ENCRYPTION` | Encryption | `tls` |
| `MAIL_FROM_ADDRESS` | From email | `noreply@agrosite.com` |
| `MAIL_FROM_NAME` | From name | `AgroSite API` |

## Build Process

Render runs these commands during build:

1. **Install Dependencies:**
   ```bash
   composer install --no-dev --optimize-autoloader
   ```

2. **Cache Configuration:**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

3. **Create Storage Directories:**
   ```bash
   mkdir -p storage/app/public/products
   mkdir -p storage/app/public/services
   ```

4. **Set Permissions:**
   ```bash
   chmod -R 775 storage
   chmod -R 775 bootstrap/cache
   ```

5. **Create Storage Link:**
   ```bash
   php artisan storage:link
   ```

## Start Process

When the service starts:

1. **Cache Configuration:**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

2. **Start Server:**
   ```bash
   php artisan serve --host=0.0.0.0 --port=$PORT
   ```

## Database Setup

### First Time Setup

1. Create database on Render
2. Get connection details
3. Add to environment variables
4. Run migrations:
   ```bash
   php artisan migrate --force
   ```
5. Seed data (optional):
   ```bash
   php artisan db:seed
   ```

### Connection String Format

Render provides connection details in this format:
```
Host: dpg-xxxxx-a.oregon-postgres.render.com
Port: 5432
Database: agrosite_db
User: agrosite_user
Password: xxxxx
```

Use these values in your environment variables.

## Storage Configuration

### Local Storage (Default)

For local storage on Render:
- Files are stored in `storage/app/public`
- Accessible via `https://your-app.onrender.com/storage/...`
- Storage persists between deployments
- Limited by Render's disk space

### S3 Storage (Recommended for Production)

For better scalability, use S3:

1. Create S3 bucket on AWS
2. Get access keys
3. Add to environment:
   ```env
   FILESYSTEM_DRIVER=s3
   AWS_ACCESS_KEY_ID=your-key
   AWS_SECRET_ACCESS_KEY=your-secret
   AWS_DEFAULT_REGION=us-east-1
   AWS_BUCKET=agrosite-storage
   ```

## Troubleshooting

### Build Fails

**Issue:** Build fails with composer errors
**Solution:**
- Check `composer.json` is valid
- Ensure all dependencies are specified
- Check PHP version compatibility

**Issue:** Build fails with permission errors
**Solution:**
- Storage permissions are set in build script
- Check build logs for specific errors

### Application Won't Start

**Issue:** 500 Internal Server Error
**Solution:**
- Check `APP_KEY` is set
- Verify database connection
- Check application logs in Render dashboard
- Ensure all required env variables are set

**Issue:** Database connection fails
**Solution:**
- Verify database credentials
- Check database is running
- Ensure database allows connections
- Test connection from Render shell

### CORS Issues

**Issue:** Frontend can't access API
**Solution:**
- Verify `FRONTEND_URL` is correct
- Check `config/cors.php` settings
- Clear config cache: `php artisan config:clear`
- Ensure frontend URL matches exactly (no trailing slash)

### Storage Issues

**Issue:** Images not accessible
**Solution:**
- Run `php artisan storage:link` in shell
- Check storage directory exists
- Verify permissions (775)
- Check `public/storage` link exists

## Monitoring

### View Logs

1. Go to service dashboard
2. Click **"Logs"** tab
3. View real-time logs
4. Filter by error level

### Check Status

- Service status in dashboard
- Health check endpoint (if configured)
- Database connection status

## Updating Your Application

1. **Push Changes:**
   ```bash
   git add .
   git commit -m "Update application"
   git push origin main
   ```

2. **Auto-Deploy:**
   - Render automatically detects changes
   - Starts new build
   - Deploys when build succeeds

3. **Manual Deploy:**
   - Go to service dashboard
   - Click **"Manual Deploy"**
   - Select branch/commit
   - Deploy

## Rollback

If deployment fails:

1. Go to service dashboard
2. Click **"Manual Deploy"**
3. Select previous successful commit
4. Click **"Deploy"**

## Performance Tips

1. **Enable Caching:**
   - Config cache: `php artisan config:cache`
   - Route cache: `php artisan route:cache`
   - View cache: `php artisan view:cache`

2. **Optimize Autoloader:**
   - Use `--optimize-autoloader` in composer install

3. **Use CDN:**
   - Serve static assets via CDN
   - Use S3 for file storage

4. **Database Optimization:**
   - Add indexes where needed
   - Use connection pooling
   - Optimize queries

## Security Checklist

- [ ] `APP_DEBUG=false` in production
- [ ] `APP_ENV=production`
- [ ] Strong database passwords
- [ ] CORS properly configured
- [ ] Rate limiting enabled
- [ ] File upload validation
- [ ] No sensitive data in logs
- [ ] HTTPS enabled (automatic on Render)

## Support Resources

- **Render Docs:** https://render.com/docs
- **Laravel Docs:** https://laravel.com/docs
- **Render Status:** https://status.render.com
- **Community:** Render Discord/Slack

## Next Steps

After successful deployment:

1. Test all API endpoints
2. Connect frontend to API
3. Set up monitoring
4. Configure backups (if using paid plan)
5. Set up custom domain (optional)

---

**Need Help?** Check the troubleshooting section or Render's documentation.

