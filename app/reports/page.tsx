import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card"
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/components/ui/tabs"
import { Badge } from "@/components/ui/badge"
import {
  BarChart,
  Bar,
  XAxis,
  YAxis,
  CartesianGrid,
  Tooltip,
  Legend,
  ResponsiveContainer,
  PieChart,
  Pie,
  Cell,
} from "recharts"

export default function ReportsPage() {
  const salesByProvider = [
    { name: "Fastweb", value: 4200 },
    { name: "Iliad", value: 3800 },
    { name: "WindTre", value: 3500 },
    { name: "Sky Wifi", value: 2800 },
    { name: "Sky TV", value: 2200 },
    { name: "Pianeta Fibra", value: 1800 },
  ]

  const monthlySales = [
    { name: "Gen", sim: 42, devices: 18, accessories: 24 },
    { name: "Feb", sim: 38, devices: 20, accessories: 26 },
    { name: "Mar", sim: 45, devices: 25, accessories: 30 },
    { name: "Apr", sim: 50, devices: 22, accessories: 28 },
    { name: "Mag", sim: 55, devices: 28, accessories: 32 },
    { name: "Giu", sim: 60, devices: 30, accessories: 35 },
  ]

  const COLORS = ["#FF6B6B", "#4ECDC4", "#FFD166", "#118AB2", "#073B4C", "#6A0572"]

  return (
    <div className="flex min-h-screen w-full flex-col">
      <main className="flex flex-1 flex-col gap-4 p-4 md:gap-8 md:p-8">
        <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
          <h1 className="text-3xl font-bold tracking-tight">Reportistica e Analisi</h1>
        </div>

        <Tabs defaultValue="sales" className="space-y-4">
          <TabsList className="bg-muted/50 p-1 w-full overflow-x-auto">
            <TabsTrigger value="sales" className="data-[state=active]:bg-white data-[state=active]:shadow-sm">
              Vendite
            </TabsTrigger>
            <TabsTrigger value="providers" className="data-[state=active]:bg-white data-[state=active]:shadow-sm">
              Gestori
            </TabsTrigger>
            <TabsTrigger value="products" className="data-[state=active]:bg-white data-[state=active]:shadow-sm">
              Prodotti
            </TabsTrigger>
          </TabsList>
          <TabsContent value="sales" className="space-y-4">
            <div className="responsive-grid-4">
              <Card>
                <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                  <CardTitle className="text-sm font-medium">Vendite Totali</CardTitle>
                </CardHeader>
                <CardContent>
                  <div className="text-2xl font-bold">€45,231.89</div>
                  <p className="text-xs text-muted-foreground">+20.1% rispetto al mese scorso</p>
                </CardContent>
              </Card>
              <Card>
                <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                  <CardTitle className="text-sm font-medium">SIM Vendute</CardTitle>
                </CardHeader>
                <CardContent>
                  <div className="text-2xl font-bold">245</div>
                  <p className="text-xs text-muted-foreground">+15% rispetto al mese scorso</p>
                </CardContent>
              </Card>
              <Card>
                <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                  <CardTitle className="text-sm font-medium">Dispositivi Venduti</CardTitle>
                </CardHeader>
                <CardContent>
                  <div className="text-2xl font-bold">87</div>
                  <p className="text-xs text-muted-foreground">+5% rispetto al mese scorso</p>
                </CardContent>
              </Card>
              <Card>
                <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                  <CardTitle className="text-sm font-medium">Accessori Venduti</CardTitle>
                </CardHeader>
                <CardContent>
                  <div className="text-2xl font-bold">132</div>
                  <p className="text-xs text-muted-foreground">+12% rispetto al mese scorso</p>
                </CardContent>
              </Card>
            </div>

            <Card>
              <CardHeader>
                <CardTitle>Vendite Mensili per Categoria</CardTitle>
                <CardDescription>Numero di prodotti venduti per categoria negli ultimi 6 mesi</CardDescription>
              </CardHeader>
              <CardContent>
                <ResponsiveContainer width="100%" height={400}>
                  <BarChart data={monthlySales}>
                    <CartesianGrid strokeDasharray="3 3" />
                    <XAxis dataKey="name" />
                    <YAxis />
                    <Tooltip />
                    <Legend />
                    <Bar dataKey="sim" fill="#FF6B6B" name="SIM" />
                    <Bar dataKey="devices" fill="#4ECDC4" name="Dispositivi" />
                    <Bar dataKey="accessories" fill="#FFD166" name="Accessori" />
                  </BarChart>
                </ResponsiveContainer>
              </CardContent>
            </Card>
          </TabsContent>
          <TabsContent value="providers" className="space-y-4">
            <Card>
              <CardHeader>
                <CardTitle>Vendite per Gestore</CardTitle>
                <CardDescription>Distribuzione delle vendite tra i diversi gestori</CardDescription>
              </CardHeader>
              <CardContent>
                <ResponsiveContainer width="100%" height={400}>
                  <PieChart>
                    <Pie
                      data={salesByProvider}
                      cx="50%"
                      cy="50%"
                      labelLine={true}
                      label={({ name, percent }) => `${name}: ${(percent * 100).toFixed(0)}%`}
                      outerRadius={150}
                      fill="#8884d8"
                      dataKey="value"
                    >
                      {salesByProvider.map((entry, index) => (
                        <Cell key={`cell-${index}`} fill={COLORS[index % COLORS.length]} />
                      ))}
                    </Pie>
                    <Tooltip formatter={(value) => `€${value}`} />
                    <Legend />
                  </PieChart>
                </ResponsiveContainer>
              </CardContent>
            </Card>
          </TabsContent>
          <TabsContent value="products" className="space-y-4">
            <Card>
              <CardHeader>
                <CardTitle>Prodotti Più Venduti</CardTitle>
                <CardDescription>I prodotti con le vendite più elevate</CardDescription>
              </CardHeader>
              <CardContent>
                <div className="space-y-8">
                  <div className="space-y-2">
                    <div className="flex items-center justify-between">
                      <div className="flex items-center gap-2">
                        <span className="font-medium">iPhone 13</span>
                        <Badge className="bg-blue-500">Dispositivo</Badge>
                      </div>
                      <span className="font-medium">€799 x 15 unità</span>
                    </div>
                    <div className="h-2 w-full rounded-full bg-muted">
                      <div className="h-2 rounded-full bg-green-500" style={{ width: "85%" }}></div>
                    </div>
                    <div className="flex justify-between text-sm text-muted-foreground">
                      <span>Vendite totali: €11,985</span>
                      <span>85% delle vendite di dispositivi</span>
                    </div>
                  </div>
                  <div className="space-y-2">
                    <div className="flex items-center justify-between">
                      <div className="flex items-center gap-2">
                        <span className="font-medium">SIM Iliad</span>
                        <Badge className="bg-red-500">SIM</Badge>
                      </div>
                      <span className="font-medium">€9.99 x 120 unità</span>
                    </div>
                    <div className="h-2 w-full rounded-full bg-muted">
                      <div className="h-2 rounded-full bg-green-500" style={{ width: "75%" }}></div>
                    </div>
                    <div className="flex justify-between text-sm text-muted-foreground">
                      <span>Vendite totali: €1,198.80</span>
                      <span>75% delle vendite di SIM</span>
                    </div>
                  </div>
                  <div className="space-y-2">
                    <div className="flex items-center justify-between">
                      <div className="flex items-center gap-2">
                        <span className="font-medium">Cuffie AirPods Pro</span>
                        <Badge className="bg-yellow-500">Accessorio</Badge>
                      </div>
                      <span className="font-medium">€249 x 25 unità</span>
                    </div>
                    <div className="h-2 w-full rounded-full bg-muted">
                      <div className="h-2 rounded-full bg-green-500" style={{ width: "65%" }}></div>
                    </div>
                    <div className="flex justify-between text-sm text-muted-foreground">
                      <span>Vendite totali: €6,225</span>
                      <span>65% delle vendite di accessori</span>
                    </div>
                  </div>
                </div>
              </CardContent>
            </Card>
          </TabsContent>
        </Tabs>
      </main>
    </div>
  )
}

