import Layout from "@/components/layout"
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card"

export default function ServicesPage() {
  const services = [
    {
      title: "Web Development",
      description: "Custom websites and web applications built with the latest technologies.",
      details:
        "Our web development services include front-end and back-end development, responsive design, e-commerce solutions, and content management systems.",
    },
    {
      title: "Mobile Applications",
      description: "Native and cross-platform mobile apps for iOS and Android.",
      details:
        "We develop intuitive, high-performance mobile applications that provide seamless user experiences across all devices.",
    },
    {
      title: "UI/UX Design",
      description: "User-centered design that enhances user experience and engagement.",
      details:
        "Our design team creates visually appealing interfaces that are intuitive, accessible, and aligned with your brand identity.",
    },
    {
      title: "Cloud Solutions",
      description: "Scalable cloud infrastructure and migration services.",
      details:
        "We help businesses leverage the power of cloud computing to improve efficiency, reduce costs, and enhance security.",
    },
    {
      title: "Digital Marketing",
      description: "Comprehensive digital marketing strategies to grow your online presence.",
      details:
        "Our marketing services include SEO, content marketing, social media management, email marketing, and PPC advertising.",
    },
    {
      title: "Consulting",
      description: "Expert advice on technology strategy and implementation.",
      details:
        "Our consultants provide insights and recommendations to help you make informed decisions about your technology investments.",
    },
  ]

  return (
    <Layout>
      <div className="container mx-auto p-6">
        <h1 className="text-3xl font-bold mb-6">Our Services</h1>
        <p className="text-lg mb-8">
          We offer a wide range of services to help your business thrive in the digital world. Our team of experts is
          ready to assist you with all your technology needs.
        </p>

        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          {services.map((service, index) => (
            <Card key={index} className="transition-all hover:shadow-md">
              <CardHeader>
                <CardTitle>{service.title}</CardTitle>
                <CardDescription>{service.description}</CardDescription>
              </CardHeader>
              <CardContent>
                <p>{service.details}</p>
              </CardContent>
            </Card>
          ))}
        </div>
      </div>
    </Layout>
  )
}

