# What's Left — Seven Islands Watamu

## Immediate (before going fully live)

### 1. Email notifications
- Get access to DNS settings for `sevenislandswatamu.com`
- Add Resend DNS records (DKIM, SPF, DMARC) to the domain registrar
- Add `RESEND_API_KEY` to Render environment variables
- Test: submit a form → email should arrive at `reservation@sevenislandswatamu.com`

### 2. Persistent image storage (Cloudflare R2)
- Create a free Cloudflare account
- Create an R2 bucket named `7island-images`, enable public access
- Get API credentials (Account ID, Access Key, Secret Key, Public URL)
- Add R2 environment variables to Render
- Uploaded images will then survive server restarts and redeployments

### 3. Real room images
- Upload actual hotel photos through Admin → Rooms → Edit → Gallery
- Set a hero image for each room
- Note: do this AFTER R2 is set up so images persist

---

## When ready to expand

### 4. Hotel #2 and #3
- Clone the `7IslandWatamu` repo
- Create new Render web service + PostgreSQL database
- Update environment variables for the new hotel
- Run setup URL to initialise the database
- Customise rooms and content via admin panel

### 5. Custom domain on Render
- Once `sevenislandswatamu.com` is registered and DNS is accessible
- Render → web service → Settings → Custom Domain
- Add a CNAME record pointing to Render

---

## Optional (v2 backlog from spec)

- Real-time availability calendar + bookable form
- Submission status pipeline (new → contacted → booked → lost)
- Internal notes per submission
- Auto-reply email to guest
- Multi-user admin with roles
- Cloudflare R2 image CDN with responsive sizes
