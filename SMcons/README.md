# S&M Infra - Premium Infrastructure Website

A professional real estate website showcasing luxury infrastructure projects in Nagpur.

## 🏗️ Project Structure

```
SMcons/
├── src/
│   ├── components/          # Reusable UI components
│   └── pages/              # HTML pages
├── assets/
│   ├── images/            # Images organized by category
│   ├── videos/            # Video files
│   ├── fonts/             # Custom fonts
│   └── icons/             # Icon files
├── styles/
│   ├── components/        # Component-specific CSS
│   ├── sections/          # Section-specific CSS
│   ├── utilities/         # Utility CSS
│   └── main.css          # Main stylesheet
├── scripts/
│   ├── components/        # UI component scripts
│   ├── sections/          # Section-specific scripts
│   ├── utils/            # Utility functions
│   └── lang/              # Language files
├── backend/
│   ├── api/               # API endpoints
│   ├── auth/              # Authentication
│   ├── config/            # Configuration
│   └── server.js          # Node.js server
├── config/                # Project configuration
├── docs/                  # Documentation
└── tests/                 # Test files
```

## 🚀 Features

- **Multi-language Support**: English, Hindi, Kannada
- **Responsive Design**: Mobile-first approach
- **Admin Panel**: Content management system
- **AI Integration**: Gemini AI for chat functionality
- **Contact Forms**: Inquiry and feedback systems
- **Project Gallery**: Filterable project showcase

## 🛠️ Technologies Used

- **Frontend**: HTML5, CSS3, JavaScript, Bootstrap 5
- **Backend**: PHP, Node.js
- **Database**: MySQL (implied)
- **AI**: Google Gemini API
- **Icons**: Font Awesome
- **Fonts**: Google Fonts (Montserrat, Playfair Display)

## 📦 Setup Instructions

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd SMcons
   ```

2. **Configure the backend**
   - Update database credentials in `backend/config/`
   - Set up API keys in environment variables

3. **Install Node.js dependencies**
   ```bash
   cd backend
   npm install
   ```

4. **Start the development server**
   ```bash
   # Node.js server
   node backend/server.js
   
   # Or use PHP built-in server
   php -S localhost:8000
   ```

## 🔧 Configuration

### Environment Variables
Create a `.env` file in the root directory:
```
GEMINI_API_KEY=your_gemini_api_key
DB_HOST=localhost
DB_NAME=sm_infra
DB_USER=username
DB_PASS=password
```

### Database Setup
1. Create a MySQL database named `sm_infra`
2. Import the provided SQL schema
3. Update credentials in configuration files

## 📝 Documentation

- [API Documentation](docs/api.md)
- [Admin Guide](docs/admin.md)
- [Deployment Guide](docs/deployment.md)

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Commit your changes
4. Push to the branch
5. Create a Pull Request

## 📄 License

This project is proprietary to S&M Infrastructures.

## 📞 Contact

- **Address**: Civil Lines, Nagpur, 440001
- **Phone**: +91 7888012200
- **Email**: info@sm-infra.in

---

© 2026 S&M Infrastructures. Built for Excellence.
