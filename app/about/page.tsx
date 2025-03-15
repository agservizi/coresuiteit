import Layout from "@/components/layout"

export default function AboutPage() {
  return (
    <Layout>
      <div className="container mx-auto p-6">
        <h1 className="text-3xl font-bold mb-6">About Us</h1>
        <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
          <div className="space-y-4">
            <p className="text-lg">
              Welcome to our company! We are dedicated to providing exceptional services and innovative solutions to
              meet your needs.
            </p>
            <p>
              Our team of experts is committed to excellence and customer satisfaction. With years of experience in the
              industry, we have developed a deep understanding of what our clients need and how to deliver results that
              exceed expectations.
            </p>
            <p>
              We believe in building long-lasting relationships with our clients based on trust, transparency, and
              mutual respect. Our approach is collaborative, ensuring that your voice is heard and your vision is
              realized.
            </p>
          </div>
          <div className="bg-muted rounded-lg p-6 space-y-4">
            <h2 className="text-xl font-semibold">Our Mission</h2>
            <p>
              To empower businesses and individuals with cutting-edge solutions that drive growth, efficiency, and
              success in an ever-evolving digital landscape.
            </p>
            <h2 className="text-xl font-semibold mt-4">Our Values</h2>
            <ul className="list-disc pl-5 space-y-2">
              <li>Innovation: We constantly seek new and better ways to solve problems.</li>
              <li>Integrity: We uphold the highest standards of honesty and ethics.</li>
              <li>Excellence: We strive for perfection in everything we do.</li>
              <li>Collaboration: We believe in the power of teamwork and partnership.</li>
              <li>Customer Focus: Your success is our success.</li>
            </ul>
          </div>
        </div>
      </div>
    </Layout>
  )
}

